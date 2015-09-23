CREATE TABLE administrators (
  name VARCHAR(32) NOT NULL,
  password VARCHAR(255) NOT NULL,
  profile INT,
  username VARCHAR(32),
  email VARCHAR(255),
  since DATE DEFAULT NOW(),
  id SERIAL
);

CREATE TABLE settings (
  module VARCHAR(255) NOT NULL,
  key VARCHAR(255) NOT NULL,
  value VARCHAR(255),
  lastchange TIMESTAMP DEFAULT NOW(),
  PRIMARY KEY(module,key)
);

/*
Default password is: 123456
To generate a different password, please run:

php -r "echo password_hash('YOUR_PASSWORD_HERE', PASSWORD_DEFAULT) . PHP_EOL;";

And paste the output before run the query below.
You still can change your password after CMS setup.
*/

INSERT INTO administrators (name, password, profile, username) VALUES
('Administrator', '$2y$10$09z91PkP4SCxD0CceWEe6egjlDZzYpoMpF4R19CSsYmDMYCtnF8MS', 1, 'admin');
