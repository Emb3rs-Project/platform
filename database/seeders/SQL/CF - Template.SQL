INSERT INTO public.templates(id, name, "values", category_id)VALUES (1, 'Boiler', '[]',1);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (2, 'Building', '[]',6);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (3, 'Burner', '[]',1);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (4, 'CHP', '[]',1);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (5, 'Cooling Equip.', '[]',1);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (6, 'Existent Sink', '[]',2);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (7, 'Existent Source', '[]',3);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (8, 'Greenhouse', '[]',6);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (9, 'Grid Connection Point', '[]',4);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (10, 'Inflow', '[]',5);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (11, 'Maintenance', '[]',5);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (12, 'Outflow', '[]',5);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (13, 'Process', '[]',5);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (14, 'Simple Sink', '[]',6);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (15, 'Simple Source', '[]',7);
INSERT INTO public.templates(id, name, "values", category_id)VALUES (16, 'Startup', '[]',5);

SELECT pg_catalog.setval(pg_get_serial_sequence('templates', 'id'), (SELECT MAX(id) FROM templates)+1);