-- NO CAMBIAR EL ORDEN
delete from pagos;
delete from detalles_ventas;
delete from ventas;
delete from clientes;
delete from productos;
delete from detalles_compras;
delete from compras;
delete from proveedores;
delete from cotizaciones;
delete from tipos_pago;
delete from categorias_producto;
delete from negocios;
delete from sectores;
delete from localidades;
delete from estados;
delete from usuarios;

insert into usuarios (id, cedula, clave, rol, esta_activo, pregunta_secreta, respuesta_secreta, id_admin) values
  (1, 12345678, /* Admin.123 */ '$2y$10$HoG.Mi9zoKT7MhhELX9aKesjOxyk1o4DGlSdy3307jAxwV3Jnm23e', 'Administrador', true, 'nombre del sistema', /* sitcav */ '$2y$10$MOBiWkg7wIWzLMJuvprhE.svQ366OfvnbfMlIrQUDQGC.a6uVUfeC', null),
  (2, 12345679, /* Vendedor.123 */ '$2y$10$UwZFBCo836pmmP9NRQjC2ufi6lQYWIDmKEi0yZhPmMh4.RqRqGmQW', 'Vendedor', true, 'nombre del sistema', /* sitcav */ '$2y$10$MOBiWkg7wIWzLMJuvprhE.svQ366OfvnbfMlIrQUDQGC.a6uVUfeC', 1),
  (3, 12345689, /* Hacker.123 */ '$2y$10$qtKkRdQdzZ1gcU2wggVzwelxLPi6FDfC.TTInshPk74ljkTg/nQNa', 'Administrador', true, 'nombre del sistema', /* sitcav */ '$2y$10$MOBiWkg7wIWzLMJuvprhE.svQ366OfvnbfMlIrQUDQGC.a6uVUfeC', null);

insert into cotizaciones (id, fecha_hora, tasa_dolar_bolivares, id_usuario) values
  (1, '2025-02-02 04:30:00', 60, 1),
  (2, '2025-02-01 04:30:00', 58, 1),
  (3, '2025-01-31 04:30:00', 57.6, 1),
  (4, '2025-02-02 04:30:00', 153.52, 3);

insert into estados (id, nombre, id_usuario) values
  (1, 'Amazonas', 1),
  (2, 'Anzoátegui', 1),
  (3, 'Apure', 1),
  (4, 'Aragua', 1),
  (5, 'Barinas', 1),
  (6, 'Bolívar', 1),
  (7, 'Carabobo', 1),
  (8, 'Cojedes', 1),
  (9, 'Delta Amacuro', 1),
  (10, 'Falcón', 1),
  (11, 'Guárico', 1),
  (12, 'Lara', 1),
  (13, 'Mérida', 1),
  (14, 'Miranda', 1),
  (15, 'Monagas', 1),
  (16, 'Nueva Esparta', 1),
  (17, 'Portuguesa', 1),
  (18, 'Sucre', 1),
  (19, 'Táchira', 1),
  (20, 'Trujillo', 1),
  (21, 'La Guaira', 1),
  (22, 'Yaracuy', 1),
  (23, 'Zulia', 1),
  (24, 'Distrito Capital', 1),
  (25, 'Dependencias Federales', 1);

insert into localidades (id, id_estado, nombre) values
  (1, 1, 'Maroa'),
  (2, 1, 'Puerto Ayacucho'),
  (3, 1, 'San Fernando de Atabapo'),
  (4, 2, 'Anaco'),
  (5, 2, 'Aragua de Barcelona'),
  (6, 2, 'Barcelona'),
  (7, 2, 'Boca de Uchire'),
  (8, 2, 'Cantaura'),
  (9, 2, 'Clarines'),
  (10, 2, 'El Chaparro'),
  (11, 2, 'El Pao Anzoátegui'),
  (12, 2, 'El Tigre'),
  (13, 2, 'El Tigrito'),
  (14, 2, 'Guanape'),
  (15, 2, 'Guanta'),
  (16, 2, 'Lechería'),
  (17, 2, 'Onoto'),
  (18, 2, 'Pariaguán'),
  (19, 2, 'Píritu'),
  (20, 2, 'Puerto La Cruz'),
  (21, 2, 'Puerto Píritu'),
  (22, 2, 'Sabana de Uchire'),
  (23, 2, 'San Mateo Anzoátegui'),
  (24, 2, 'San Pablo Anzoátegui'),
  (25, 2, 'San Tomé'),
  (26, 2, 'Santa Ana de Anzoátegui'),
  (27, 2, 'Santa Fe Anzoátegui'),
  (28, 2, 'Santa Rosa'),
  (29, 2, 'Soledad'),
  (30, 2, 'Urica'),
  (31, 2, 'Valle de Guanape'),
  (43, 3, 'Achaguas'),
  (44, 3, 'Biruaca'),
  (45, 3, 'Bruzual'),
  (46, 3, 'El Amparo'),
  (47, 3, 'El Nula'),
  (48, 3, 'Elorza'),
  (49, 3, 'Guasdualito'),
  (50, 3, 'Mantecal'),
  (51, 3, 'Puerto Páez'),
  (52, 3, 'San Fernando de Apure'),
  (53, 3, 'San Juan de Payara'),
  (54, 4, 'Barbacoas'),
  (55, 4, 'Cagua'),
  (56, 4, 'Camatagua'),
  (58, 4, 'Choroní'),
  (59, 4, 'Colonia Tovar'),
  (60, 4, 'El Consejo'),
  (61, 4, 'La Victoria'),
  (62, 4, 'Las Tejerías'),
  (63, 4, 'Magdaleno'),
  (64, 4, 'Maracay'),
  (65, 4, 'Ocumare de La Costa'),
  (66, 4, 'Palo Negro'),
  (67, 4, 'San Casimiro'),
  (68, 4, 'San Mateo'),
  (69, 4, 'San Sebastián'),
  (70, 4, 'Santa Cruz de Aragua'),
  (71, 4, 'Tocorón'),
  (72, 4, 'Turmero'),
  (73, 4, 'Villa de Cura'),
  (74, 4, 'Zuata'),
  (75, 5, 'Barinas'),
  (76, 5, 'Barinitas'),
  (77, 5, 'Barrancas'),
  (78, 5, 'Calderas'),
  (79, 5, 'Capitanejo'),
  (80, 5, 'Ciudad Bolivia'),
  (81, 5, 'El Cantón'),
  (82, 5, 'Las Veguitas'),
  (83, 5, 'Libertad de Barinas'),
  (84, 5, 'Sabaneta'),
  (85, 5, 'Santa Bárbara de Barinas'),
  (86, 5, 'Socopó'),
  (87, 6, 'Caicara del Orinoco'),
  (88, 6, 'Canaima'),
  (89, 6, 'Ciudad Bolívar'),
  (90, 6, 'Ciudad Piar'),
  (91, 6, 'El Callao'),
  (92, 6, 'El Dorado'),
  (93, 6, 'El Manteco'),
  (94, 6, 'El Palmar'),
  (95, 6, 'El Pao'),
  (96, 6, 'Guasipati'),
  (97, 6, 'Guri'),
  (98, 6, 'La Paragua'),
  (99, 6, 'Matanzas'),
  (100, 6, 'Puerto Ordaz'),
  (101, 6, 'San Félix'),
  (102, 6, 'Santa Elena de Uairén'),
  (103, 6, 'Tumeremo'),
  (104, 6, 'Unare'),
  (105, 6, 'Upata'),
  (106, 7, 'Bejuma'),
  (107, 7, 'Belén'),
  (108, 7, 'Campo de Carabobo'),
  (109, 7, 'Canoabo'),
  (110, 7, 'Central Tacarigua'),
  (111, 7, 'Chirgua'),
  (112, 7, 'Ciudad Alianza'),
  (113, 7, 'El Palito'),
  (114, 7, 'Guacara'),
  (115, 7, 'Guigue'),
  (116, 7, 'Las Trincheras'),
  (117, 7, 'Los Guayos'),
  (118, 7, 'Mariara'),
  (119, 7, 'Miranda'),
  (120, 7, 'Montalbán'),
  (121, 7, 'Morón'),
  (122, 7, 'Naguanagua'),
  (123, 7, 'Puerto Cabello'),
  (124, 7, 'San Joaquín'),
  (125, 7, 'Tocuyito'),
  (126, 7, 'Urama'),
  (127, 7, 'Valencia'),
  (128, 7, 'Vigirimita'),
  (129, 8, 'Aguirre'),
  (130, 8, 'Apartaderos Cojedes'),
  (131, 8, 'Arismendi'),
  (132, 8, 'Camuriquito'),
  (133, 8, 'El Baúl'),
  (134, 8, 'El Limón'),
  (135, 8, 'El Pao Cojedes'),
  (136, 8, 'El Socorro'),
  (137, 8, 'La Aguadita'),
  (138, 8, 'Las Vegas'),
  (139, 8, 'Libertad de Cojedes'),
  (140, 8, 'Mapuey'),
  (141, 8, 'Piñedo'),
  (142, 8, 'Samancito'),
  (143, 8, 'San Carlos'),
  (144, 8, 'Sucre'),
  (145, 8, 'Tinaco'),
  (146, 8, 'Tinaquillo'),
  (147, 8, 'Vallecito'),
  (148, 9, 'Tucupita'),
  (149, 24, 'Caracas'),
  (150, 24, 'El Junquito'),
  (151, 10, 'Adícora'),
  (152, 10, 'Boca de Aroa'),
  (153, 10, 'Cabure'),
  (154, 10, 'Capadare'),
  (155, 10, 'Capatárida'),
  (156, 10, 'Chichiriviche'),
  (157, 10, 'Churuguara'),
  (158, 10, 'Coro'),
  (159, 10, 'Cumarebo'),
  (160, 10, 'Dabajuro'),
  (161, 10, 'Judibana'),
  (162, 10, 'La Cruz de Taratara'),
  (163, 10, 'La Vela de Coro'),
  (164, 10, 'Los Taques'),
  (165, 10, 'Maparari'),
  (166, 10, 'Mene de Mauroa'),
  (167, 10, 'Mirimire'),
  (168, 10, 'Pedregal'),
  (169, 10, 'Píritu Falcón'),
  (170, 10, 'Pueblo Nuevo Falcón'),
  (171, 10, 'Puerto Cumarebo'),
  (172, 10, 'Punta Cardón'),
  (173, 10, 'Punto Fijo'),
  (174, 10, 'San Juan de Los Cayos'),
  (175, 10, 'San Luis'),
  (176, 10, 'Santa Ana Falcón'),
  (177, 10, 'Santa Cruz De Bucaral'),
  (178, 10, 'Tocopero'),
  (179, 10, 'Tocuyo de La Costa'),
  (180, 10, 'Tucacas'),
  (181, 10, 'Yaracal'),
  (182, 11, 'Altagracia de Orituco'),
  (183, 11, 'Cabruta'),
  (184, 11, 'Calabozo'),
  (185, 11, 'Camaguán'),
  (196, 11, 'Chaguaramas Guárico'),
  (197, 11, 'El Socorro'),
  (198, 11, 'El Sombrero'),
  (199, 11, 'Las Mercedes de Los Llanos'),
  (200, 11, 'Lezama'),
  (201, 11, 'Onoto'),
  (202, 11, 'Ortíz'),
  (203, 11, 'San José de Guaribe'),
  (204, 11, 'San Juan de Los Morros'),
  (205, 11, 'San Rafael de Laya'),
  (206, 11, 'Santa María de Ipire'),
  (207, 11, 'Tucupido'),
  (208, 11, 'Valle de La Pascua'),
  (209, 11, 'Zaraza'),
  (210, 12, 'Aguada Grande'),
  (211, 12, 'Atarigua'),
  (212, 12, 'Barquisimeto'),
  (213, 12, 'Bobare'),
  (214, 12, 'Cabudare'),
  (215, 12, 'Carora'),
  (216, 12, 'Cubiro'),
  (217, 12, 'Cují'),
  (218, 12, 'Duaca'),
  (219, 12, 'El Manzano'),
  (220, 12, 'El Tocuyo'),
  (221, 12, 'Guaríco'),
  (222, 12, 'Humocaro Alto'),
  (223, 12, 'Humocaro Bajo'),
  (224, 12, 'La Miel'),
  (225, 12, 'Moroturo'),
  (226, 12, 'Quíbor'),
  (227, 12, 'Río Claro'),
  (228, 12, 'Sanare'),
  (229, 12, 'Santa Inés'),
  (230, 12, 'Sarare'),
  (231, 12, 'Siquisique'),
  (232, 12, 'Tintorero'),
  (233, 13, 'Apartaderos Mérida'),
  (234, 13, 'Arapuey'),
  (235, 13, 'Bailadores'),
  (236, 13, 'Caja Seca'),
  (237, 13, 'Canaguá'),
  (238, 13, 'Chachopo'),
  (239, 13, 'Chiguara'),
  (240, 13, 'Ejido'),
  (241, 13, 'El Vigía'),
  (242, 13, 'La Azulita'),
  (243, 13, 'La Playa'),
  (244, 13, 'Lagunillas Mérida'),
  (245, 13, 'Mérida'),
  (246, 13, 'Mesa de Bolívar'),
  (247, 13, 'Mucuchíes'),
  (248, 13, 'Mucujepe'),
  (249, 13, 'Mucuruba'),
  (250, 13, 'Nueva Bolivia'),
  (251, 13, 'Palmarito'),
  (252, 13, 'Pueblo Llano'),
  (253, 13, 'Santa Cruz de Mora'),
  (254, 13, 'Santa Elena de Arenales'),
  (255, 13, 'Santo Domingo'),
  (256, 13, 'Tabáy'),
  (257, 13, 'Timotes'),
  (258, 13, 'Torondoy'),
  (259, 13, 'Tovar'),
  (260, 13, 'Tucani'),
  (261, 13, 'Zea'),
  (262, 14, 'Araguita'),
  (263, 14, 'Carrizal'),
  (264, 14, 'Caucagua'),
  (265, 14, 'Chaguaramas Miranda'),
  (266, 14, 'Charallave'),
  (267, 14, 'Chirimena'),
  (268, 14, 'Chuspa'),
  (269, 14, 'Cúa'),
  (270, 14, 'Cupira'),
  (271, 14, 'Curiepe'),
  (272, 14, 'El Guapo'),
  (273, 14, 'El Jarillo'),
  (274, 14, 'Filas de Mariche'),
  (275, 14, 'Guarenas'),
  (276, 14, 'Guatire'),
  (277, 14, 'Higuerote'),
  (278, 14, 'Los Anaucos'),
  (279, 14, 'Los Teques'),
  (280, 14, 'Ocumare del Tuy'),
  (281, 14, 'Panaquire'),
  (282, 14, 'Paracotos'),
  (283, 14, 'Río Chico'),
  (284, 14, 'San Antonio de Los Altos'),
  (285, 14, 'San Diego de Los Altos'),
  (286, 14, 'San Fernando del Guapo'),
  (287, 14, 'San Francisco de Yare'),
  (288, 14, 'San José de Los Altos'),
  (289, 14, 'San José de Río Chico'),
  (290, 14, 'San Pedro de Los Altos'),
  (291, 14, 'Santa Lucía'),
  (292, 14, 'Santa Teresa'),
  (293, 14, 'Tacarigua de La Laguna'),
  (294, 14, 'Tacarigua de Mamporal'),
  (295, 14, 'Tácata'),
  (296, 14, 'Turumo'),
  (297, 15, 'Aguasay'),
  (298, 15, 'Aragua de Maturín'),
  (299, 15, 'Barrancas del Orinoco'),
  (300, 15, 'Caicara de Maturín'),
  (301, 15, 'Caripe'),
  (302, 15, 'Caripito'),
  (303, 15, 'Chaguaramal'),
  (305, 15, 'Chaguaramas Monagas'),
  (307, 15, 'El Furrial'),
  (308, 15, 'El Tejero'),
  (309, 15, 'Jusepín'),
  (310, 15, 'La Toscana'),
  (311, 15, 'Maturín'),
  (312, 15, 'Miraflores'),
  (313, 15, 'Punta de Mata'),
  (314, 15, 'Quiriquire'),
  (315, 15, 'San Antonio de Maturín'),
  (316, 15, 'San Vicente Monagas'),
  (317, 15, 'Santa Bárbara'),
  (318, 15, 'Temblador'),
  (319, 15, 'Teresen'),
  (320, 15, 'Uracoa'),
  (321, 16, 'Altagracia'),
  (322, 16, 'Boca de Pozo'),
  (323, 16, 'Boca de Río'),
  (324, 16, 'El Espinal'),
  (325, 16, 'El Valle del Espíritu Santo'),
  (326, 16, 'El Yaque'),
  (327, 16, 'Juangriego'),
  (328, 16, 'La Asunción'),
  (329, 16, 'La Guardia'),
  (330, 16, 'Pampatar'),
  (331, 16, 'Porlamar'),
  (332, 16, 'Puerto Fermín'),
  (333, 16, 'Punta de Piedras'),
  (334, 16, 'San Francisco de Macanao'),
  (335, 16, 'San Juan Bautista'),
  (336, 16, 'San Pedro de Coche'),
  (337, 16, 'Santa Ana de Nueva Esparta'),
  (338, 16, 'Villa Rosa'),
  (339, 17, 'Acarigua'),
  (340, 17, 'Agua Blanca'),
  (341, 17, 'Araure'),
  (342, 17, 'Biscucuy'),
  (343, 17, 'Boconoito'),
  (344, 17, 'Campo Elías'),
  (345, 17, 'Chabasquén'),
  (346, 17, 'Guanare'),
  (347, 17, 'Guanarito'),
  (348, 17, 'La Aparición'),
  (349, 17, 'La Misión'),
  (350, 17, 'Mesa de Cavacas'),
  (351, 17, 'Ospino'),
  (352, 17, 'Papelón'),
  (353, 17, 'Payara'),
  (354, 17, 'Pimpinela'),
  (355, 17, 'Píritu de Portuguesa'),
  (356, 17, 'San Rafael de Onoto'),
  (357, 17, 'Santa Rosalía'),
  (358, 17, 'Turén'),
  (359, 18, 'Altos de Sucre'),
  (360, 18, 'Araya'),
  (361, 18, 'Cariaco'),
  (362, 18, 'Carúpano'),
  (363, 18, 'Casanay'),
  (364, 18, 'Cumaná'),
  (365, 18, 'Cumanacoa'),
  (366, 18, 'El Morro Puerto Santo'),
  (367, 18, 'El Pilar'),
  (368, 18, 'El Poblado'),
  (369, 18, 'Guaca'),
  (370, 18, 'Guiria'),
  (371, 18, 'Irapa'),
  (372, 18, 'Manicuare'),
  (373, 18, 'Mariguitar'),
  (374, 18, 'Río Caribe'),
  (375, 18, 'San Antonio del Golfo'),
  (376, 18, 'San José de Aerocuar'),
  (377, 18, 'San Vicente de Sucre'),
  (378, 18, 'Santa Fe de Sucre'),
  (379, 18, 'Tunapuy'),
  (380, 18, 'Yaguaraparo'),
  (381, 18, 'Yoco'),
  (382, 19, 'Abejales'),
  (383, 19, 'Borota'),
  (384, 19, 'Bramon'),
  (385, 19, 'Capacho'),
  (386, 19, 'Colón'),
  (387, 19, 'Coloncito'),
  (388, 19, 'Cordero'),
  (389, 19, 'El Cobre'),
  (390, 19, 'El Pinal'),
  (391, 19, 'Independencia'),
  (392, 19, 'La Fría'),
  (393, 19, 'La Grita'),
  (394, 19, 'La Pedrera'),
  (395, 19, 'La Tendida'),
  (396, 19, 'Las Delicias'),
  (397, 19, 'Las Hernández'),
  (398, 19, 'Lobatera'),
  (399, 19, 'Michelena'),
  (400, 19, 'Palmira'),
  (401, 19, 'Pregonero'),
  (402, 19, 'Queniquea'),
  (403, 19, 'Rubio'),
  (404, 19, 'San Antonio del Tachira'),
  (405, 19, 'San Cristobal'),
  (406, 19, 'San José de Bolívar'),
  (407, 19, 'San Josecito'),
  (408, 19, 'San Pedro del Río'),
  (409, 19, 'Santa Ana Táchira'),
  (410, 19, 'Seboruco'),
  (411, 19, 'Táriba'),
  (412, 19, 'Umuquena'),
  (413, 19, 'Ureña'),
  (414, 20, 'Batatal'),
  (415, 20, 'Betijoque'),
  (416, 20, 'Boconó'),
  (417, 20, 'Carache'),
  (418, 20, 'Chejende'),
  (419, 20, 'Cuicas'),
  (420, 20, 'El Dividive'),
  (421, 20, 'El Jaguito'),
  (422, 20, 'Escuque'),
  (423, 20, 'Isnotú'),
  (424, 20, 'Jajó'),
  (425, 20, 'La Ceiba'),
  (426, 20, 'La Concepción de Trujllo'),
  (427, 20, 'La Mesa de Esnujaque'),
  (428, 20, 'La Puerta'),
  (429, 20, 'La Quebrada'),
  (430, 20, 'Mendoza Fría'),
  (431, 20, 'Meseta de Chimpire'),
  (432, 20, 'Monay'),
  (433, 20, 'Motatán'),
  (434, 20, 'Pampán'),
  (435, 20, 'Pampanito'),
  (436, 20, 'Sabana de Mendoza'),
  (437, 20, 'San Lázaro'),
  (438, 20, 'Santa Ana de Trujillo'),
  (439, 20, 'Tostós'),
  (440, 20, 'Trujillo'),
  (441, 20, 'Valera'),
  (442, 21, 'Carayaca'),
  (443, 21, 'Litoral'),
  (444, 25, 'Archipiélago Los Roques'),
  (445, 22, 'Aroa'),
  (446, 22, 'Boraure'),
  (447, 22, 'Campo Elías de Yaracuy'),
  (448, 22, 'Chivacoa'),
  (449, 22, 'Cocorote'),
  (450, 22, 'Farriar'),
  (451, 22, 'Guama'),
  (452, 22, 'Marín'),
  (453, 22, 'Nirgua'),
  (454, 22, 'Sabana de Parra'),
  (455, 22, 'Salom'),
  (456, 22, 'San Felipe'),
  (457, 22, 'San Pablo de Yaracuy'),
  (458, 22, 'Urachiche'),
  (459, 22, 'Yaritagua'),
  (460, 22, 'Yumare'),
  (461, 23, 'Bachaquero'),
  (462, 23, 'Bobures'),
  (463, 23, 'Cabimas'),
  (464, 23, 'Campo Concepción'),
  (465, 23, 'Campo Mara'),
  (466, 23, 'Campo Rojo'),
  (467, 23, 'Carrasquero'),
  (468, 23, 'Casigua'),
  (469, 23, 'Chiquinquirá'),
  (470, 23, 'Ciudad Ojeda'),
  (471, 23, 'El Batey'),
  (472, 23, 'El Carmelo'),
  (473, 23, 'El Chivo'),
  (474, 23, 'El Guayabo'),
  (475, 23, 'El Mene'),
  (476, 23, 'El Venado'),
  (477, 23, 'Encontrados'),
  (478, 23, 'Gibraltar'),
  (479, 23, 'Isla de Toas'),
  (480, 23, 'La Concepción del Zulia'),
  (481, 23, 'La Paz'),
  (482, 23, 'La Sierrita'),
  (483, 23, 'Lagunillas del Zulia'),
  (484, 23, 'Las Piedras de Perijá'),
  (485, 23, 'Los Cortijos'),
  (486, 23, 'Machiques'),
  (487, 23, 'Maracaibo'),
  (488, 23, 'Mene Grande'),
  (489, 23, 'Palmarejo'),
  (490, 23, 'Paraguaipoa'),
  (491, 23, 'Potrerito'),
  (492, 23, 'Pueblo Nuevo del Zulia'),
  (493, 23, 'Puertos de Altagracia'),
  (494, 23, 'Punta Gorda'),
  (495, 23, 'Sabaneta de Palma'),
  (496, 23, 'San Francisco'),
  (497, 23, 'San José de Perijá'),
  (498, 23, 'San Rafael del Moján'),
  (499, 23, 'San Timoteo'),
  (500, 23, 'Santa Bárbara Del Zulia'),
  (501, 23, 'Santa Cruz de Mara'),
  (502, 23, 'Santa Cruz del Zulia'),
  (503, 23, 'Santa Rita'),
  (504, 23, 'Sinamaica'),
  (505, 23, 'Tamare'),
  (506, 23, 'Tía Juana'),
  (507, 23, 'Villa del Rosario'),
  (508, 21, 'La Guaira'),
  (509, 21, 'Catia La Mar'),
  (510, 21, 'Macuto'),
  (511, 21, 'Naiguatá'),
  (512, 25, 'Archipiélago Los Monjes'),
  (513, 25, 'Isla La Tortuga y Cayos adyacentes'),
  (514, 25, 'Isla La Sola'),
  (515, 25, 'Islas Los Testigos'),
  (516, 25, 'Islas Los Frailes'),
  (517, 25, 'Isla La Orchila'),
  (518, 25, 'Archipiélago Las Aves'),
  (519, 25, 'Isla de Aves'),
  (520, 25, 'Isla La Blanquilla'),
  (521, 25, 'Isla de Patos'),
  (522, 25, 'Islas Los Hermanos'),
  (523, 13, 'El Pinar');

insert into sectores (id, nombre, id_localidad) values
  (1, 'La Batea', 523),
  (2, 'Las Casitas', 523),
  (3, 'Las Tejas', 523),
  (4, 'Las Malvinas', 523),
  (5, 'Los Naranjos', 523);

insert into negocios (id, rif, nombre, telefono, id_localidad, id_sector) values
  (1, 'J-12345678-9', 'Ferretería El Constructor', '02121234567', 149, 1),
  (2, 'J-12345678-0', 'Ferretería El Constructor', '02121234567', 149, 2),
  (3, 'J-12345678-1', 'Ferretería El Constructor', '02121234567', 149, 3),
  (4, 'J-12345678-2', 'Ferretería El Constructor', '02121234567', 149, 4),
  (5, 'J-12345678-3', 'Ferretería El Constructor', '02121234567', 149, 5);

insert into proveedores (id, rif, nombre, telefono, id_estado) values
  (1, 'J-12345678-9', 'Ferretería El Constructor', '02121234567', 24),
  (2, 'J-12345678-0', 'Ferretería El Constructor', '02121234567', 24),
  (3, 'J-12345678-1', 'Ferretería El Constructor', '02121234567', 24),
  (4, 'J-12345678-2', 'Ferretería El Constructor', '02121234567', 24),
  (5, 'J-12345678-3', 'Ferretería El Constructor', '02121234567', 24);

insert into categorias_producto (id, nombre, id_usuario) values
  (1, 'Routers', 1),
  (2, 'Modems', 1),
  (3, 'Switches', 1),
  (5, 'Accesorios', 1),
  (6, 'Cables', 1);

insert into productos (id, codigo, nombre, descripcion, precio_unitario_actual_dolares, cantidad_disponible, dias_garantia, dias_apartado, id_categoria, id_proveedor) values
  (1, 'RTR-001', 'Router Cisco 1000', 'Router de 4 puertos', 100, 10, 30, 7, 1, 1),
  (2, 'RTR-002', 'Router Cisco 2000', 'Router de 8 puertos', 200, 10, 30, 30, 1, 1),
  (3, 'RTR-003', 'Router Cisco 3000', 'Router de 16 puertos', 300, 10, 30, 30, 1, 1),
  (4, 'RTR-004', 'Router Cisco 4000', 'Router de 32 puertos', 400, 10, 30, 30, 1, 1),
  (5, 'RTR-005', 'Router Cisco 5000', 'Router de 64 puertos', 500, 10, 30, 30, 1, 1),
  (6, 'MDM-001', 'Modem Cisco 1000', 'Modem de 4 puertos', 100, 10, 30, 30, 2, 1),
  (7, 'MDM-002', 'Modem Cisco 2000', 'Modem de 8 puertos', 200, 10, 30, 30, 2, 1),
  (8, 'MDM-003', 'Modem Cisco 3000', 'Modem de 16 puertos', 300, 10, 30, 30, 2, 1),
  (9, 'MDM-004', 'Modem Cisco 4000', 'Modem de 32 puertos', 400, 10, 30, 30, 2, 1),
  (10, 'MDM-005', 'Modem Cisco 5000', 'Modem de 64 puertos', 500, 10, 30, 30, 2, 1),
  (11, 'SWT-001', 'Switch Cisco 1000', 'Switch de 4 puertos', 100, 10, 30, 30, 3, 1),
  (12, 'SWT-002', 'Switch Cisco 2000', 'Switch de 8 puertos', 200, 10, 30, 30, 3, 1),
  (13, 'SWT-003', 'Switch Cisco 3000', 'Switch de 16 puertos', 300, 10, 30, 30, 3, 1),
  (14, 'SWT-004', 'Switch Cisco 4000', 'Switch de 32 puertos', 400, 10, 30, 30, 3, 1),
  (15, 'SWT-005', 'Switch Cisco 5000', 'Switch de 64 puertos', 500, 10, 30, 30, 3, 1),
  (16, 'ACC-001', 'Accesorio Cisco 1000', 'Accesorio de 4 puertos', 100, 10, 30, 30, 5, 1),
  (17, 'ACC-002', 'Accesorio Cisco 2000', 'Accesorio de 8 puertos', 200, 10, 30, 30, 5, 1),
  (18, 'ACC-003', 'Accesorio Cisco 3000', 'Accesorio de 16 puertos', 300, 10, 30, 30, 5, 1),
  (19, 'ACC-004', 'Accesorio Cisco 4000', 'Accesorio de 32 puertos', 400, 10, 30, 30, 5, 1),
  (20, 'ACC-005', 'Accesorio Cisco 5000', 'Accesorio de 64 puertos', 500, 10, 30, 30, 5, 1),
  (21, 'CBL-001', 'Cable Cisco 1000', 'Cable de 4 puertos', 100, 10, 30, 30, 6, 1),
  (22, 'CBL-002', 'Cable Cisco 2000', 'Cable de 8 puertos', 200, 10, 30, 30, 6, 1),
  (23, 'CBL-003', 'Cable Cisco 3000', 'Cable de 16 puertos', 300, 10, 30, 30, 6, 1),
  (24, 'CBL-004', 'Cable Cisco 4000', 'Cable de 32 puertos', 400, 10, 30, 30, 6, 1),
  (25, 'CBL-005', 'Cable Cisco 5000', 'Cable de 64 puertos', 500, 10, 30, 30, 6, 1);

insert into compras (id, fecha_hora, cotizacion_dolar_bolivares, id_proveedor) values
  (1, '2020-01-01 00:00:00', 10000, 1),
  (2, '2020-01-02 00:00:00', 10000, 1),
  (3, '2020-01-03 00:00:00', 10000, 1),
  (4, '2020-01-04 00:00:00', 10000, 1),
  (5, '2020-01-05 00:00:00', 10000, 1);

insert into detalles_compras (id, cantidad, precio_unitario_fijo_dolares, id_producto, id_compra) values
  (1, 10, 100, 1, 1),
  (2, 10, 200, 2, 1),
  (3, 10, 300, 3, 1),
  (4, 10, 400, 4, 1),
  (5, 10, 500, 5, 1),
  (6, 10, 100, 6, 2),
  (7, 10, 200, 7, 2),
  (8, 10, 300, 8, 2),
  (9, 10, 400, 9, 2),
  (10, 10, 500, 10, 2),
  (11, 10, 100, 11, 3),
  (12, 10, 200, 12, 3),
  (13, 10, 300, 13, 3),
  (14, 10, 400, 14, 3),
  (15, 10, 500, 15, 3),
  (16, 10, 100, 16, 4),
  (17, 10, 200, 17, 4),
  (18, 10, 300, 18, 4),
  (19, 10, 400, 19, 4),
  (20, 10, 500, 20, 4),
  (21, 10, 100, 21, 5),
  (22, 10, 200, 22, 5),
  (23, 10, 300, 23, 5),
  (24, 10, 400, 24, 5),
  (25, 10, 500, 25, 5);

insert into clientes (id, cedula, nombres, apellidos, telefono, id_localidad) values
  (1, 12345678, 'Juan', 'Pérez', '04121234567', 149),
  (2, 12345679, 'Pedro', 'Gómez', '04121234567', 149),
  (3, 12345670, 'María', 'González', '04121234567', 149),
  (4, 12345671, 'José', 'Hernández', '04121234567', 149),
  (5, 12345672, 'Ana', 'Martínez', '04121234567', 149);

insert into ventas (id, fecha_hora, id_cliente) values
  (1, '2020-01-01 00:00:00', 1),
  (2, '2020-01-02 00:00:00', 1),
  (3, '2020-01-03 00:00:00', 1),
  (4, '2020-01-04 00:00:00', 1),
  (5, '2020-01-05 00:00:00', 1);

insert into detalles_ventas (id, cantidad, precio_unitario_fijo_dolares, esta_apartado, id_producto, id_venta) values
  (1, 10, 100, 0, 1, 1),
  (2, 10, 200, 0, 2, 1),
  (3, 10, 300, 0, 3, 1),
  (4, 10, 400, 0, 4, 1),
  (5, 10, 500, 0, 5, 1),
  (6, 10, 100, 0, 6, 2),
  (7, 10, 200, 0, 7, 2),
  (8, 10, 300, 0, 8, 2),
  (9, 10, 400, 0, 9, 2),
  (10, 10, 500, 0, 10, 2),
  (11, 10, 100, 0, 11, 3),
  (12, 10, 200, 0, 12, 3),
  (13, 10, 300, 0, 13, 3),
  (14, 10, 400, 0, 14, 3),
  (15, 10, 500, 0, 15, 3),
  (16, 10, 100, 0, 16, 4),
  (17, 10, 200, 0, 17, 4),
  (18, 10, 300, 0, 18, 4),
  (19, 10, 400, 0, 19, 4),
  (20, 10, 500, 0, 20, 4),
  (21, 10, 100, 0, 21, 5),
  (22, 10, 200, 0, 22, 5),
  (23, 10, 300, 0, 23, 5),
  (24, 10, 400, 0, 24, 5),
  (25, 10, 500, 0, 25, 5);

insert into tipos_pago (id, nombre, id_usuario) values
  (1, 'Efectivo', 1),
  (2, 'Transferencia', 1),
  (3, 'Punto de Venta', 1);

insert into pagos (id, fecha_hora, cotizacion_dolar_bolivares, monto, id_tipo_pago, id_detalle_venta) values
  (1, '2020-01-01 00:00:00', 10000, 1000, 1, 1),
  (2, '2020-01-02 00:00:00', 10000, 2000, 1, 2),
  (3, '2020-01-03 00:00:00', 10000, 3000, 1, 3),
  (4, '2020-01-04 00:00:00', 10000, 4000, 1, 4),
  (5, '2020-01-05 00:00:00', 10000, 5000, 1, 5);
