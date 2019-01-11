drop table if exists report_upload;
create table report_upload (
  id int unsigned not null auto_increment primary key,
  data json,
  oid varchar(100) generated always as (data ->> '$.trace_id'),
  guid char(36) not null default '',
  gnum int unsigned not null default 0,
  report_order_status tinyint(1) unsigned not null default 0,
  report_order_log int unsigned not null default 0,
  report_logistics_status tinyint(1) unsigned not null default 0,
  report_logistics_log int unsigned not null default 0,
  report_receipts_status tinyint(1) unsigned not null default 0,
  report_receipts_log int unsigned not null default 0,
  report_inventory_status tinyint(1) unsigned not null default 0,
  report_inventory_log int unsigned not null default 0,
  report_waybill_status tinyint(1) unsigned not null default 0,
  report_waybill_log int unsigned not null default 0,
  create_time timestamp not null default current_timestamp,
  unique index export_upload_oid (oid)
);

drop table if exists report_log;
create table report_log (
  id int unsigned not null auto_increment primary key,
  type varchar(10) not null,#order,logistics,receipts,inventory,waybill
  content text,
  response text,
  status varchar(10),
  message varchar(1000),
  create_time timestamp not null default current_timestamp
);
