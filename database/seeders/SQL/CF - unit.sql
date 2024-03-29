INSERT INTO public.units(id, name, symbol)VALUES (1,'Area - Square meter','m^2');
INSERT INTO public.units(id, name, symbol)VALUES (2,'Cost- €/kiloWatt','€/kW');
INSERT INTO public.units(id, name, symbol)VALUES (3,'Cost - €/kiloWatt-hour','€/kWh');
INSERT INTO public.units(id, name, symbol)VALUES (4,'Degrees - Âº','º');
INSERT INTO public.units(id, name, symbol)VALUES (5,'Power- kiloWatt','kW');
INSERT INTO public.units(id, name, symbol)VALUES (6,'Mass Flow - kg/h','kg/h');
INSERT INTO public.units(id, name, symbol)VALUES (7,'Flow - Cubic meter per hour','m^3/h');
INSERT INTO public.units(id, name, symbol)VALUES (8,'Flow - m^3/person','m^3/person');
INSERT INTO public.units(id, name, symbol)VALUES (9,'Flowrate - m^3/s','m^3/s');
INSERT INTO public.units(id, name, symbol)VALUES (10,'Height - Meter','m');
INSERT INTO public.units(id, name, symbol)VALUES (11,'Percentage','%');
INSERT INTO public.units(id, name, symbol)VALUES (12,'Pressure - Bar','Bar');
INSERT INTO public.units(id, name, symbol)VALUES (13,'Temperature - Celcius','ºC');
INSERT INTO public.units(id, name, symbol)VALUES (14,'Thermal - J/K','J/');
INSERT INTO public.units(id, name, symbol)VALUES (15,'Thermal - J/m^2K','J/m^2');
INSERT INTO public.units(id, name, symbol)VALUES (16,'Thermal - W/m^2','W/m^2');
INSERT INTO public.units(id, name, symbol)VALUES (17,'Thermal - W/m^2K','W/m^2K');
INSERT INTO public.units(id, name, symbol)VALUES (18,'Thermal - W/m^2K^2','W/m^2K^2');
INSERT INTO public.units(id, name, symbol)VALUES (19,'Time - 1/h','1/h');
INSERT INTO public.units(id, name, symbol)VALUES (20,'Time - Day','day(s)');
INSERT INTO public.units(id, name, symbol)VALUES (21,'Time - Hour','h');
INSERT INTO public.units(id, name, symbol)VALUES (22,'Time - Month','month(s)');
INSERT INTO public.units(id, name, symbol)VALUES (23,'Volume - Cubic meter','m^3');
INSERT INTO public.units(id, name, symbol)VALUES (24,'Thermal - Hourly Profile','kWh');
INSERT INTO public.units(id, name, symbol)VALUES (9999,'N/A','');
INSERT INTO public.units(id, name, symbol)VALUES (25,'Cost- €/year','€/year');
INSERT INTO public.units(id, name, symbol)VALUES (26,'Emssions - kgCO2/kWh','kgCO2/kWh');
INSERT INTO public.units(id, name, symbol)VALUES (27,'Cp','kJ/kg.K');
INSERT INTO public.units(id, name, symbol)VALUES (28,'Thermal','W/m.K');
INSERT INTO public.units(id, name, symbol)VALUES (29,'Mass','kg');

SELECT pg_catalog.setval(pg_get_serial_sequence('units', 'id'), 27);