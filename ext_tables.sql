#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
  privacy_settings text,
  about_me text,
  public_profile tinyint(4) unsigned DEFAULT '0' NOT NULL,
);
