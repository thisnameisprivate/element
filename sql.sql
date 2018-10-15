#创建用户表
CREATE TABLE user (
id int key auto_increment,
username varchar(32) unique not null,
password varchar(32) not null,
addtime timestamp default current_timestamp
);
insert into user(username, password) values ('admin', md5(123));
#创建科室表
CREATE TABLE hospital (
id int key auto_increment,
hospital varchar(32) unique not null,
iden varchar(32) not null,
addtime timestamp default current_timestamp not null
);
#创建病种表
create table alldiseases (
id int key auto_increment,
diseases varchar(32) not null,
tableName varchar(32) unique not null ,
addtime timestamp default current_timestamp not null
);
#创建就诊类型表
create table fromaddress (
id int key auto_increment,
fromaddress varchar(32) unique not null,
addtime timestamp not null default current_timestamp
);