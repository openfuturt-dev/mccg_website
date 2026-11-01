# Google Ads lead tracking — setup and verification

How a successful contact request becomes a Google Ads conversion on mc-cg.com, and how to check it in production.

## How it works

1. `POST /contact` validates the form, stores the `ContactMessage`, and sends the Resend notification.
2. Only when both succeed, `ContactController` flashes a random `mccg_lead_id` (UUID). Validation errors and mail failures flash nothing.
3. The next page renders that ID once as `<script type="application/json" data-mccg-lead>`. A reload does not render it again.
4. `resources/js/lead-tracking.js` reports the lead once, and only after the visitor clicked **Accepter** in the cookie banner (now, or later on the same page):
   - pushes `mccg_generate_lead` to `dataLayer` (for GTM), and
   - if `MCCG_GOOGLE_ADS_CONVERSION_SEND_TO` is set, sends `gtag('event', 'conversion', { send_to, transaction_id })`.
5. Duplicates are blocked by an in-page flag, a per-tab `sessionStorage` marker (history replays), and `transaction_id` (Google Ads deduplicates on it).

The `dataLayer` event contains no personal data:

```js
{ event: 'mccg_generate_lead', lead_source: 'contact_form', form_name: 'contact', page_path: '/contact', lead_id: '<uuid>' }
```

## Choose one conversion path

Count the conversion in **one** place only.

| | Path A — direct tag (default, active today) | Path B — Google Tag Manager |
|---|---|---|
| Who sends the conversion | the site, via gtag.js | a Google Ads tag in GTM |
| `MCCG_GOOGLE_ADS_CONVERSION_SEND_TO` | `AW-987825813/<label>` | `""` (empty) |
| Google Ads conversion tag in GTM | **none** | yes (below) |

Path A needs no GTM work. Choose Path B only if you want marketing to manage tags in GTM.

## Google Ads checks (both paths)

1. **Objectifs → Conversions → Récapitulatif**: open the action listed under *Envois de formulaires de lead*.
2. **Configuration de la balise → Utiliser Google Tag Manager** shows its *ID de conversion* and *Libellé de conversion*. Confirm they match `MCCG_GOOGLE_ADS_CONVERSION_SEND_TO` (`AW-<ID>/<Libellé>`). If the label differs, put the real one in the production `.env`. Never use a guessed value.
3. In the same goal, look for other website actions (for example one auto-created from detected form submissions). An action that nothing fires stays *inactive*. Remove it, or set it to *secondaire*.
4. Recommended action settings: *Nombre* = **Une**; the action is *principale* and included in the campaigns' goals.
5. **Paramètres du compte → Taggage automatique** must be enabled.

## GTM configuration (Path B only)

Container `GTM-N64QTKK` is currently published with **no tags**.

1. **Variable**: Data Layer Variable `DLV - lead_id`, name `lead_id`.
2. **Trigger** `MCCG - Successful Lead`: Custom Event, event name `mccg_generate_lead`, *All Custom Events*.
3. **Tag** `Google Ads - Lead conversion`: *Google Ads Conversion Tracking*.
   - Conversion ID and Conversion Label: copy them from Google Ads (step 2 above).
   - Transaction ID: `{{DLV - lead_id}}`.
   - Trigger: `MCCG - Successful Lead`.
   - Consent settings: keep the built-in checks (`ad_storage`, `ad_user_data`). The site only emits the event after acceptance anyway.
4. **Tag** `Conversion Linker`: trigger *All Pages*.
5. **Optional GA4 lead event** (GA4 `G-E0GCGNTBLL` already loads on every page through the site's Google tag, so do not add a GA4 configuration tag in GTM):
   - Add a GA4 Event tag `generate_lead` on the same trigger.
   - Do not also import `generate_lead` into Google Ads as a *principale* conversion, or each lead counts twice.
6. **Submit → Publish**.
7. In the production `.env`, set `MCCG_GOOGLE_ADS_CONVERSION_SEND_TO=""`, then run `php artisan optimize`.

## Production verification

1. Deploy the commit, including `public/build`, and run `php artisan optimize`.
2. Open `https://mc-cg.com/contact` in a private window, with GTM **Preview** / [Tag Assistant](https://tagassistant.google.com/) connected.
3. Click **Accepter**. Tag Assistant should show the consent update to *granted*.
4. Submit the form with an invalid email: you should see **no** `mccg_generate_lead`.
5. Submit one real, valid request. After the success message:
   - `mccg_generate_lead` appears exactly once in the dataLayer.
   - Path A: a Google Ads `conversion` hit to `AW-987825813` with a `transaction_id`.
   - Path B: tag `Google Ads - Lead conversion` *Fired*.
6. Reload the page and use Back/Forward: no new event.
7. Repeat in a new private window, clicking **Refuser**: no lead event, no conversion hit.
8. Delete the test lead in the admin (`/admin/contact-messages`) if needed.

## Timing and remaining account-side checks

- The *Non validée* (unverified) status usually clears within a few hours of the first received conversion hit.
- Conversions can take up to 24 h to appear in reports.
- *Inactive* means no conversion in the last 7 days. It needs real, consented leads to clear.
- If the tag fires correctly in Tag Assistant but the action stays unverified after 24–48 h:
  - recheck that the label belongs to *that* action (Google Ads checks, step 2);
  - check that no ad blocker was active during the test;
  - use **Diagnostics** on the conversion action page.
- Conversions are only sent for visitors who accept the banner. If few visitors accept, the action can stay *inactive* even when the setup is correct. Sending cookieless pings for visitors who refuse (Consent Mode "advanced") is a business and legal decision. It would also require updating the banner and the privacy page, which today promise that refusing disables tracking.
