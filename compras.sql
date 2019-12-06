create database compras;
	use compras;
    

create table usuarios(
	idUser integer not null auto_increment primary key,
    user varchar(15) not null,
    senha varchar(15) not null,
    dtcria datetime default now(),
    statuss varchar(1) default ''
);
alter table usuarios add tipo varchar(13) default 'Comum' after senha;
insert into usuarios(user, senha)
	values('admin','admin123');
    
select * from usuarios;



select user, senha, tipo,
case statuss
when 'D' then
		'DESATIVADO'
else
		'ATIVO'
end statuss
from usuarios;

#drop database compras;