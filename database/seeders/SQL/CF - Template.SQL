INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (1, 'Boiler', 0, '[]',1);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (2, 'Building', 2, '[]',6);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (3, 'Burner', 0, '[]',1);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (4, 'CHP', 0, '[]',1);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (5, 'Cooling Equip.', 0, '[]',1);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (6, 'Existent Sink',4, '[]',2);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (7, 'Existent Source', 2, '[]',3);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (8, 'Greenhouse', 3, '[]',6);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (9, 'Grid Connection Point', 3, '[]',4);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (10, 'Inflow', 0, '[]',5);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (11, 'Set Point Maintenance/Evaporation', 0, '[]',5);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (12, 'Outflow', 0, '[]',5);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (13, 'Process', 0, '[]',5);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (14, 'Simple Sink', 1, '[]',6);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (15, 'Simple Source', 1, '[]',7);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (16, 'Startup DEPRECATED', 0, '[]',5);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (17, 'ORC', 0, '[]',8);
INSERT INTO public.templates(id, name,"order", "values", category_id)VALUES (18, 'Pinch - Isolated Streams', 0, '[]',8);

SELECT pg_catalog.setval(pg_get_serial_sequence('templates', 'id'), (SELECT MAX(id) FROM templates)+1);
