INSERT INTO "categorias" VALUES (1,1,'Smartphones'),
 (2,1,'Laptops'),
 (3,1,'Accesorios'),
 (4,1,'Tablets');
INSERT INTO "clientes" VALUES (1,'Ana','Rodríguez','22334455','0424-111-2222',NULL,1),
 (2,'Pedro','Martínez','33445566','0414-222-3333',NULL,1),
 (3,'Luisa','Fernández','44556677','0426-333-4444',NULL,1);
INSERT INTO "cotizaciones" VALUES (1,1,'2026-04-09 20:16:46.569580',35.5);
INSERT INTO "detalles_apartados" VALUES (1,1,2,1,1199.99),
 (2,1,2,1,1199.99),
 (3,1,2,1,1199.99),
 (4,1,2,1,1199.99),
 (5,1,2,1,1199.99),
 (6,1,2,1,1199.99);
INSERT INTO "detalles_ventas" VALUES (1,1,1,899.99,1,0),
 (2,1,5,249.99,2,0),
 (3,2,1,899.99,1,0),
 (4,2,1,899.99,1,0),
 (5,2,1,899.99,1,''),
 (6,2,1,899.99,1,''),
 (7,2,1,899.99,1,''),
 (8,2,1,899.99,1,''),
 (9,2,1,899.99,1,''),
 (10,2,1,899.99,1,'');
INSERT INTO "estados" VALUES (1,1,'Miranda');
INSERT INTO "localidades" VALUES (1,1,'Caracas');
INSERT INTO "negocios" VALUES (1,1,1,'TechStore Venezuela','J-12345678-9','0212-555-1234',NULL);
INSERT INTO "pagos_apartados" VALUES (1,1,10,'2026-04-09 21:14:38','Abono inicial'),
 (2,1,10,'2026-04-09 21:27:18','Abono inicial'),
 (3,1,10,'2026-04-12 14:29:24','Abono inicial'),
 (4,1,10,'2026-04-12 14:29:30','Abono inicial'),
 (5,1,10,'2026-04-12 14:30:15','Abono inicial'),
 (6,1,10,'2026-04-12 14:30:22','Abono inicial');
INSERT INTO "productos" VALUES (1,'Samsung Galaxy S24','Smartphone de última generación','SAM-S24-001',NULL,1,1,899.99,22,365,15,'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=400'),
 (2,'iPhone 15 Pro','iPhone con chip A17 Pro','APL-IP15P-001',NULL,1,1,1199.99,15,365,20,'https://images.unsplash.com/photo-1592286927505-1a9f33a8441f?w=400'),
 (3,'Laptop Dell Inspiron 15','Laptop para uso profesional','DELL-INS15-001',NULL,2,2,649.99,10,730,30,'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400'),
 (4,'MacBook Air M2','Laptop ultraligera de Apple','APL-MBA-M2-001',NULL,2,1,1299.99,8,365,30,'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400'),
 (5,'AirPods Pro 2','Audífonos con cancelación de ruido','APL-APP2-001',NULL,3,1,249.99,48,365,7,'https://images.unsplash.com/photo-1606841837239-c5a1a4a07af7?w=400'),
 (6,'Samsung Galaxy Tab S9','Tablet Android premium','SAM-TABS9-001',NULL,4,1,799.99,5,365,15,'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400');
INSERT INTO "proveedores" VALUES (1,1,1,1,'TechSupply International','J-98765432-1','0212-555-9876',NULL),
 (2,1,1,NULL,'ElectroDistribuidora CA','J-55544433-2','0212-555-4433',NULL);
INSERT INTO "sectores" VALUES (1,1,'Centro');
INSERT INTO "tipos_pago" VALUES (1,1,'Efectivo'),
 (2,1,'Transferencia'),
 (3,1,'Tarjeta de Débito'),
 (4,1,'Tarjeta de Crédito'),
 (5,1,'Pago Móvil');
INSERT INTO "usuarios" VALUES (1,'12345678','$2y$10$JlyAfulFPBmL1Ktpy26E8ecxIa1EiUAxXSw/YofE4cZ1eassf6qSu','Encargado',1,'Juan Pérez (Encargado)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 (2,'87654321',X'24326224313024675250416839577a777938564b335967686e76367775686445507752437a4d35716c7450426c776764586f416c47764e535a614a65','Empleado Superior',1,'María García (Emp. Superior)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 (3,'11223344',X'24326224313024396f5455543646493534696f656f473235486655722e632e434b704e302f6a51536c312f767871323436597a494b776662644e5547','Vendedor',1,'Carlos López (Vendedor)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "ventas" VALUES (1,1,NULL,'2026-04-09 20:16:46.569580',0);
