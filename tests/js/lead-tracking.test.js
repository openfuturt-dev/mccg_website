import assert from 'node:assert/strict';
import { test } from 'node:test';
import { createLeadTracker, LEAD_EVENT, readConfirmedLead } from '../../resources/js/lead-tracking.js';

const lead = { id: '0b6f8c1e-5d1a-4a3e-9d7f-2f1d0c9b8a71', form: 'contact', sendTo: 'AW-987825813/g_upCMH2m7sYEJWNhNcD' };

const fakeStorage = () => {
    const values = new Map();
    return {
        getItem: (key) => (values.has(key) ? values.get(key) : null),
        setItem: (key, value) => values.set(key, String(value)),
    };
};

const fakeWindow = ({ consent = 'accepted', sessionStorage = fakeStorage() } = {}) => {
    const win = {
        dataLayer: [],
        gtagCalls: [],
        mccgGoogleConsent: consent,
        location: { pathname: '/contact' },
        sessionStorage,
    };
    win.gtag = (...args) => win.gtagCalls.push(args);
    return win;
};

const fakeDocument = (json) => ({
    querySelector: (selector) => (selector === 'script[data-mccg-lead]' && json !== undefined ? { textContent: json } : null),
});

const leadEvents = (win) => win.dataLayer.filter((entry) => entry.event === LEAD_EVENT);

test('a confirmed lead with consent emits one lead event and one Ads conversion', () => {
    const win = fakeWindow();

    assert.equal(createLeadTracker(win, lead)(), true);

    assert.deepEqual(leadEvents(win), [{
        event: 'mccg_generate_lead',
        lead_source: 'contact_form',
        form_name: 'contact',
        page_path: '/contact',
        lead_id: lead.id,
    }]);
    assert.deepEqual(win.gtagCalls, [['event', 'conversion', { send_to: lead.sendTo, transaction_id: lead.id }]]);
});

test('no lead on the page means nothing is emitted', () => {
    const win = fakeWindow();

    assert.equal(createLeadTracker(win, readConfirmedLead(fakeDocument()))(), false);
    assert.equal(createLeadTracker(win, null)(), false);

    assert.equal(win.dataLayer.length, 0);
    assert.equal(win.gtagCalls.length, 0);
});

test('malformed or empty lead payloads are ignored', () => {
    assert.equal(readConfirmedLead(fakeDocument('not json')), null);
    assert.equal(readConfirmedLead(fakeDocument('{"id":""}')), null);
    assert.equal(readConfirmedLead(fakeDocument('null')), null);
    assert.deepEqual(readConfirmedLead(fakeDocument(JSON.stringify(lead))), lead);
});

test('repeated calls on the same page emit only once', () => {
    const win = fakeWindow();
    const track = createLeadTracker(win, lead);

    track();
    track();
    track();

    assert.equal(leadEvents(win).length, 1);
    assert.equal(win.gtagCalls.length, 1);
});

test('replaying the success page in the same tab does not emit again', () => {
    const sessionStorage = fakeStorage();
    const firstLoad = fakeWindow({ sessionStorage });
    const replayedLoad = fakeWindow({ sessionStorage });

    assert.equal(createLeadTracker(firstLoad, lead)(), true);
    assert.equal(createLeadTracker(replayedLoad, lead)(), false);

    assert.equal(leadEvents(replayedLoad).length, 0);
    assert.equal(replayedLoad.gtagCalls.length, 0);
});

test('a new submission in the same tab is tracked separately', () => {
    const sessionStorage = fakeStorage();
    const win = fakeWindow({ sessionStorage });

    createLeadTracker(win, lead)();
    createLeadTracker(win, { ...lead, id: 'f3a1d2c4-1111-4222-8333-944455556666' })();

    assert.equal(leadEvents(win).length, 2);
});

test('declined or missing consent emits nothing', () => {
    for (const consent of ['declined', null, '']) {
        const win = fakeWindow({ consent });

        assert.equal(createLeadTracker(win, lead)(), false);
        assert.equal(win.dataLayer.length, 0);
        assert.equal(win.gtagCalls.length, 0);
    }
});

test('accepting consent after the success page emits the pending lead once', () => {
    const win = fakeWindow({ consent: null });
    const track = createLeadTracker(win, lead);

    track();
    assert.equal(leadEvents(win).length, 0);

    win.mccgGoogleConsent = 'accepted';
    track();
    track();

    assert.equal(leadEvents(win).length, 1);
    assert.equal(win.gtagCalls.length, 1);
});

test('GTM-only setup emits the dataLayer event without a direct Ads conversion', () => {
    const win = fakeWindow();

    createLeadTracker(win, { ...lead, sendTo: null })();

    assert.equal(leadEvents(win).length, 1);
    assert.equal(win.gtagCalls.length, 0);
});

test('tracking still works when browser storage is blocked', () => {
    const blocked = { getItem: () => { throw new Error('denied'); }, setItem: () => { throw new Error('denied'); } };
    const win = fakeWindow({ sessionStorage: blocked });
    const track = createLeadTracker(win, lead);

    assert.equal(track(), true);
    assert.equal(track(), false);
    assert.equal(leadEvents(win).length, 1);
});

test('the lead event never carries personal data', () => {
    const win = fakeWindow();

    createLeadTracker(win, lead)();

    assert.deepEqual(Object.keys(leadEvents(win)[0]).sort(), ['event', 'form_name', 'lead_id', 'lead_source', 'page_path']);
});
