-- to store users login data
create table users (
user_id int(10) NOT NULL AUTO_INCREMENT, 
user_name varchar (30) NOT NULL,
user_passwod varchar (255) NOT NULL,
user_date DATETIME NOT NULL,
last_login DATETIME NOT NULL, 
UNIQUE INDEX user_name_unique(user_name),
PRIMARY KEY (user_id)
)
