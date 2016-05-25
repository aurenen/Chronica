CREATE TABLE categories (
    cat_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(25) NOT NULL,
    permalink VARCHAR(25) NOT NULL,
    description VARCHAR(200) NOT NULL
) ENGINE=MyISAM;
--
CREATE TABLE settings (
    set_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    set_key VARCHAR(50) NOT NULL,
    set_value VARCHAR(255) NOT NULL,
    description VARCHAR(200) NOT NULL
) ENGINE=MyISAM;
--
CREATE TABLE entry_meta (
    ent_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description VARCHAR(200),
    added TIMESTAMP NOT NULL,
    modified TIMESTAMP NOT NULL,
    published TINYINT(1)
) ENGINE=MyISAM;
--
CREATE TABLE entries (
    ent_id INT(10) UNSIGNED,
    body TEXT
) ENGINE=MyISAM;
--
CREATE TABLE category_has_entry (
    cat_id INT(10) UNSIGNED NOT NULL,
    ent_id INT(10) UNSIGNED NOT NULL
) ENGINE=MyISAM;