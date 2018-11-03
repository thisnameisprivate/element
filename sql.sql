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
#创建客服表
create table custservice (
id int key auto_increment,
custservice varchar(32) unique not null,
addtime timestamp not null default current_timestamp
);
#创建来院状态表
create table arrivalStatus (
id int key auto_increment,
arrivalStatus varchar(32) unique not null,
addtime timestamp not null default current_timestamp
);
#创建权限表
create table management (
id int key auto_increment,
pid varchar(32) unique not null,
resready varchar(8) default 0,
reswrite varchar(8) default 0,
resupdate varchar(8) default 0,
resdelete varchar(8) default 0,
sysready varchar(8) default 0,
syswrite varchar(8) default 0,
sysupdate varchar(8) default 0,
sysdelete varchar(8) default 0,
listready varchar(8) default 0,
listwrite varchar(8) default 0,
listupdate varchar(8) default 0,
listdelete varchar(8) default 0,
setready varchar(8) default 0,
setwrite varchar(8) default 0,
setupdate varchar(8) default 0,
setdelete varchar(8) default 0,
myready varchar(8) default 0,
mywrite varchar(8) default 0,
myupdate varchar(8) default 0,
mydelete varchar(8) default 0,
manageready varchar(8) default 0,
managewrite varchar(8) default 0,
manageupdate varchar(8) default 0,
managedelete varchar(8) default 0,
logready varchar(8) default 0
);