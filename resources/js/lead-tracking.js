export const LEAD_EVENT = 'mccg_generate_lead';

// Reads the lead the server confirmed on this page load, if any.
export const readConfirmedLead = (doc) => {
    const element = doc.querySelector('script[data-mccg-lead]');
    if (!element) return null;

    try {
        const lead = JSON.parse(element.textContent);
        return lead && typeof lead.id === 'string' && lead.id !== '' ? lead : null;
    } catch (_) {
        return null;
    }
};

// Returns a function that reports the confirmed lead at most once, and only after consent.
export const createLeadTracker = (win, lead) => {
    let sent = false;
    const storageKey = lead ? `mccg_lead_tracked:${lead.id}` : null;

    const alreadyTracked = () => {
        try {
            return win.sessionStorage.getItem(storageKey) !== null;
        } catch (_) {
            return false;
        }
    };

    return () => {
        if (sent || !lead || win.mccgGoogleConsent !== 'accepted') return false;

        // A history navigation can replay the cached success page in the same tab.
        if (alreadyTracked()) {
            sent = true;
            return false;
        }

        win.dataLayer = win.dataLayer || [];
        win.dataLayer.push({
            event: LEAD_EVENT,
            lead_source: 'contact_form',
            form_name: lead.form,
            page_path: win.location.pathname,
            lead_id: lead.id,
        });

        if (lead.sendTo && typeof win.gtag === 'function') {
            win.gtag('event', 'conversion', {
                send_to: lead.sendTo,
                transaction_id: lead.id,
            });
        }

        sent = true;
        try {
            win.sessionStorage.setItem(storageKey, '1');
        } catch (_) {
            // Google Ads still deduplicates on transaction_id.
        }

        return true;
    };
};
