create table Customer
(
    id varchar(15) not null primary key,
    firstname varchar(10) not null,
    lastname varchar(10) not null,
    passwd varchar(20) not null,
    phone varchar(15) not null,
    email varchar(20) not null,
    home_address text,
    post_address text,
    birthday date,
    school varchar(20),
    balance float default 0.0,
    Statement text
)character set = utf8;

insert into Customer (id,firstname,lastname,passwd,phone,email,home_address,post_address,birthday,school)values("15120001","鹏涛","宁","npt123456","13813838438","ji1tao@zz.com","123Road","345Road","1998-12-12","上海大学");
insert into Customer (id,firstname,lastname,passwd,phone,email,home_address,post_address,birthday,school)values("15120002","Penies","Tao","npt123456","13813838438","ji2tao@zz.com","123Road","345Road","1998-12-12","上海大学");
insert into Customer (id,firstname,lastname,passwd,phone,email,home_address,post_address,birthday,school)values("15120003","Dick","Ning","npt123456","13813838438","ji3tao@zz.com","123Road","345Road","1998-12-12","上海大学");
insert into Customer (id,firstname,lastname,passwd,phone,email,home_address,post_address,birthday,school)values("15120004","Brother","Tao","npt123456","13813838438","ji4tao@zz.com","123Road","345Road","1998-12-12","上海大学");

create table Admin
(
    id varchar(15) not null primary key,
    name varchar(10) not null,
    passwd varchar(20) not null,
    phone varchar(15) not null,
    email varchar(20) not null,
    privilege int(4) not null
)character set = utf8;

insert into Admin (id,name,passwd,phone,email,privilege)values("0001","黄子韬","123456","13813838438","wulitaotoa@zz.com",5);
insert into Admin (id,name,passwd,phone,email,privilege)values("0002","吴亦凡","123456","13813838438","shabi@zz.com",1);

create table House
(
    id varchar(10) not null primary key,
    name varchar(20) not null,
    area varchar(5) not null,
    beds varchar(5) not null,
    toilets varchar(5) not null,
    people varchar(5) not null,
    pets BOOLEAN default FALSE,
    smoking BOOLEAN default FALSE,
    inrto text
)character set = utf8;

insert into House (id,name,area,beds,toilets,people,pets,smoking) values("001","房子1","30","1","1","2",TRUE,TRUE);
insert into House (id,name,area,beds,toilets,people,pets,smoking) values("002","房子2","50","2","2","3",FALSE,TRUE);

create table HouseOrder
(
    id varchar(15) not null primary key,
    memberId varchar(15) not null references Customer(id),
    adminId varchar(15) not null references Admin(id),
    HouseId varchar(15) not null references House(id),
    status varchar(10) default "created",
    Price float(5) default 0.0,
    FromDate date not null,
    ToDate date not null,
    CreateDate date not null,
    LastChange date not null,
    more_intro text
)character set = utf8;

insert into HouseOrder (id,memberId,adminId,HouseId,Price,FromDate,ToDate,CreateDate,LastChange) values("00000001","15120001","0001","1",300,"2018-09-26","2018-09-27","2018-08-26","2018-08-27");
insert into HouseOrder (id,memberId,adminId,HouseId,Price,FromDate,ToDate,CreateDate,LastChange) values("00000002","15120001","0001","1",500,"2018-09-26","2018-09-27","2018-08-25","2018-08-27");


create table Message
(
    cus_id varchar(15) not null references Customer(id),
    admin_id varchar(15) not null references Admin(id),
    CustomerMessage text,
    CustomeDate date,
    ReplyMessage text,
    ReplyDate text,
    status varchar(10) default "unread",
    primary key(cus_id,admin_id)
)character set = utf8;

create table RefundApply
(
    cus_id varchar(15) not null references Customer(id),
    admin_id varchar(15) not null references Admin(id),
    num float not null,
    apply_date date not null,
    status varchar(10) default "created",
    cus_reason text not null,
    man_reson text,
    primary key(cus_id,admin_id)
)
