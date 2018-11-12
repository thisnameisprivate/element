#创建数据表
CREATE TABLE gyxhyynk (
id int key not null auto_increment,
name varchar(15) not null,
old int not null,
phone bigint(20) not null,
qq bigint(20) not null,
diseases varchar(30) not null,
fromAddress varchar(15) not null,
switch varchar(15) not null default '外地',
sex varchar(15) not null default '男',
desc1 varchar(1500) default null,
expert varchar(10) not null,
oldDate date not null,
desc2 varchar(300) not null,
status varchar(15) not null,
newDate date not null,
currentTime timestamp null default current_timestamp,
custService varchar(30) not null,
KEY `oldDate` (`oldDate`),
KEY `status` (`status`)
);
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
hospital varchar(32) not null,
tableName varchar(32) unique not null,
addtime timestamp default current_timestamp not null
);
#创建病种表
create table alldiseases (
id int key auto_increment,
diseases varchar(32) not null,
tableName varchar(32) not null ,
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
create table arrivalstatus (
id int key auto_increment,
arrivalStatus varchar(32) unique not null,
addtime timestamp not null default current_timestamp
);
#创建权限表
create table management (
id int key auto_increment,
pid varchar(32) unique not null,
resready varchar(10) default '',
reswrite varchar(10) default '',
resupdate varchar(10) default '',
resdelete varchar(10) default '',
sysready varchar(10) default '',
syswrite varchar(10) default '',
sysupdate varchar(10) default '',
sysdelete varchar(10) default '',
listready varchar(10) default '',
listwrite varchar(10) default '',
listupdate varchar(10) default '',
listdelete varchar(10) default '',
setready varchar(10) default '',
setwrite varchar(10) default '',
setupdate varchar(10) default '',
setdelete varchar(10) default '',
myready varchar(10) default '',
mywrite varchar(10) default '',
myupdate varchar(10) default '',
mydelete varchar(10) default '',
manageready varchar(10) default '',
managewrite varchar(10) default '',
manageupdate varchar(10) default '',
managedelete varchar(10) default '',
logready varchar(10) default '',
addtime timestamp default current_timestamp not null
);
# Management 默认账号权限添加
insert into management (pid, resready, reswrite, resupdate, resdelete, sysready, syswrite, sysupdate, sysdelete, listready, listwrite, listupdate, listdelete, setready, setwrite, setupdate, setdelete, myready, mywrite, myupdate, mydelete, manageready, managewrite, manageupdate, managedelete, logready) values
('admin', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on','on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on');

