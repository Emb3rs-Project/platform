INSERT INTO public.categories(id, name, type)VALUES (1,'equipment','equipment');
INSERT INTO public.categories(id, name, type)VALUES (2,'existent_sink','sink');
INSERT INTO public.categories(id, name, type)VALUES (3,'existent_source','source');
INSERT INTO public.categories(id, name, type)VALUES (4,'grid_connection_point','source');
INSERT INTO public.categories(id, name, type)VALUES (5,'process','process');
INSERT INTO public.categories(id, name, type)VALUES (6,'sink','sink');
INSERT INTO public.categories(id, name, type)VALUES (7,'source','source');
INSERT INTO public.categories(id, name, type)VALUES (8,'simulation','simulation');


SELECT pg_catalog.setval(pg_get_serial_sequence('categories', 'id'), (SELECT MAX(id) FROM categories)+1);