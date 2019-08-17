CREATE TABLE employee
(
    id int NOT NULL,
    name varchar(50) NOT NULL,
    surname varchar(50),
  CONSTRAINT employee_pk PRIMARY KEY (id)
);

CREATE TABLE machine
(
    id int NOT NULL,
    name varchar(100) NOT NULL,
    surname varchar(50),
    CONSTRAINT machine_pk PRIMARY KEY (id)
);

CREATE TABLE employee_rel_machine
(
    employee_id int NOT NULL,
    machine_id int NOT NULL,
    CONSTRAINT fk_employee FOREIGN KEY (employee_id) REFERENCES employee(id),
    CONSTRAINT fk_machine FOREIGN KEY (machine_id) REFERENCES machine(id)
);

create table user
(
    id                     int auto_increment primary key,
    username                  varchar(100) not null,
    phone                  varchar(40)  null,
    password               varchar(40)  null,
    salt                   varchar(40)  null,
    last_login             datetime     null comment '(DC2Type:datetime_immutable)',
    n_incorrect_login      int          null,
    blocked                tinyint(1)   null,
    notify_legal           smallint(6)  null,
    is_activated           tinyint(1)   not null,
    created_at             datetime     not null comment '(DC2Type:datetime_immutable)',
    blocked_until          datetime     null comment '(DC2Type:datetime_immutable)',
    last_incorrect_login   datetime     null comment '(DC2Type:datetime_immutable)',
    reset_token            varchar(64)  null,
    reset_token_expires_at datetime     null comment '(DC2Type:datetime_immutable)',
    password_changed_at    datetime     null comment '(DC2Type:datetime_immutable)',
    constraint UNIQ_8D93D649F85E0677
        unique (username)
);


create table site
(
    id                     int auto_increment primary key,
    name                  varchar(100) not null,
    is_active             tinyint(1)   not null,
    created_at             datetime     not null comment '(DC2Type:datetime_immutable)'
);

CREATE TABLE employee_rel_site
(
    employee_id int NOT NULL,
    site_id int NOT NULL,
    CONSTRAINT fk_employee_site_e FOREIGN KEY (employee_id) REFERENCES employee(id),
    CONSTRAINT fk_employee_site_s FOREIGN KEY (site_id) REFERENCES site(id)
);



