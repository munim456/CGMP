<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "MedicalClinic",
  "name": "{{ setting('clinic_name') }}",
  "url": "{{ config('app.url') }}",
  "telephone": "{{ setting('phone') }}",
  "email": "{{ setting('contact_email') }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ setting('address_line1') }}",
    "addressLocality": "Wollongong",
    "addressRegion": "NSW",
    "postalCode": "2502",
    "addressCountry": "AU"
  },
  "openingHours": "{{ setting('opening_hours_schema') }}",
  "medicalSpecialty": ["GeneralPractice"]
}
</script>
