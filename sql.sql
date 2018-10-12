#创建用户表
CREATE TABLE user (
id int key auto_increment,
username varchar(32) unique not null,
password varchar(32) not null,
addtime timestamp default current_timestamp
);
insert into user(username, password) values ('admin', md5(123));