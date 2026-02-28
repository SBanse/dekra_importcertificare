--
-- DEKRA Importcertificate24 – Datenbanktabellen
--

CREATE TABLE tx_dekraimportcertificate_domain_model_certificaterequest (
    uid               INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    pid               INT(11) DEFAULT 0 NOT NULL,
    tstamp            INT(11) UNSIGNED DEFAULT 0 NOT NULL,
    crdate            INT(11) UNSIGNED DEFAULT 0 NOT NULL,
    cruser_id         INT(11) UNSIGNED DEFAULT 0 NOT NULL,
    deleted           TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL,
    hidden            TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL,
    sorting           INT(11) DEFAULT 0 NOT NULL,

    -- Fahrzeugdaten
    vin               VARCHAR(17) DEFAULT '' NOT NULL,
    vehicle_make      VARCHAR(100) DEFAULT '' NOT NULL,
    vehicle_model     VARCHAR(100) DEFAULT '' NOT NULL,
    vehicle_year      INT(4) DEFAULT 0 NOT NULL,
    vehicle_category  VARCHAR(50) DEFAULT '' NOT NULL,
    import_country    VARCHAR(3) DEFAULT '' NOT NULL,
    target_country    VARCHAR(3) DEFAULT 'DE' NOT NULL,
    certificate_type  VARCHAR(50) DEFAULT '' NOT NULL,

    -- Kundendaten
    salutation        VARCHAR(20) DEFAULT '' NOT NULL,
    first_name        VARCHAR(100) DEFAULT '' NOT NULL,
    last_name         VARCHAR(100) DEFAULT '' NOT NULL,
    company           VARCHAR(200) DEFAULT '' NOT NULL,
    street            VARCHAR(200) DEFAULT '' NOT NULL,
    zip               VARCHAR(20) DEFAULT '' NOT NULL,
    city              VARCHAR(100) DEFAULT '' NOT NULL,
    country           VARCHAR(3) DEFAULT 'DE' NOT NULL,
    phone             VARCHAR(50) DEFAULT '' NOT NULL,
    email             VARCHAR(200) DEFAULT '' NOT NULL,

    -- Weitere Angaben
    additional_info   TEXT,
    urgent_processing TINYINT(1) DEFAULT 0 NOT NULL,
    privacy_accepted  TINYINT(1) DEFAULT 0 NOT NULL,
    terms_accepted    TINYINT(1) DEFAULT 0 NOT NULL,

    -- Status & Tracking
    status            VARCHAR(20) DEFAULT 'new' NOT NULL,
    reference_number  VARCHAR(50) DEFAULT '' NOT NULL,
    request_date      INT(11) DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY reference (reference_number),
    KEY status (status)
);
