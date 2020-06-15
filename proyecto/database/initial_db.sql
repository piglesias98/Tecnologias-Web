DROP TABLE categorias;

CREATE TABLE `categorias` (
  `categorias_id` int NOT NULL,
  `receta_id` int NOT NULL,
  PRIMARY KEY (`categorias_id`,`receta_id`),
  KEY `id_idx` (`receta_id`),
  CONSTRAINT `categorias_id` FOREIGN KEY (`categorias_id`) REFERENCES `lista_categorias` (`id`),
  CONSTRAINT `receta_id` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO categorias VALUES("2","32");
INSERT INTO categorias VALUES("3","32");
INSERT INTO categorias VALUES("2","33");
INSERT INTO categorias VALUES("6","33");
INSERT INTO categorias VALUES("7","34");



DROP TABLE comentarios;

CREATE TABLE `comentarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_receta` int DEFAULT NULL,
  `comentario` mediumtext,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_receta_idx` (`id_receta`),
  KEY `id_idx` (`id_usuario`),
  CONSTRAINT `id` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `id_comentario` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO comentarios VALUES("24","14","34","Me ha quedado buena, eh?","2020-06-15");
INSERT INTO comentarios VALUES("25","13","34","Tiene buena pinta, se la voy a hacer a los padres de Fiona cuando vengan!","2020-06-15");
INSERT INTO comentarios VALUES("26","13","33","Yo mismo me he puesto un tres porque la hice el otro día y me salió fatal, que alguien añada otra receta parecida porfi!","2020-06-15");
INSERT INTO comentarios VALUES("27","5","33","¿Sabes que puedes borrarla tú mismo? En mis recetas o debajo de la misma hay un botón Borrar (y otro editar por si has descubierto la fórmula)","2020-06-15");
INSERT INTO comentarios VALUES("28","5","33","Por cierto, yo sí le he puesto buena nota","2020-06-15");
INSERT INTO comentarios VALUES("29","","33","Ey a mí sí me ha gustado! Aunque no puedo votar.. me tendré que hacer una cuenta","2020-06-15");



DROP TABLE fotos;

CREATE TABLE `fotos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_receta` int DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_receta_idx` (`id_receta`),
  CONSTRAINT `id_receta` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO fotos VALUES("20","33","5ee7ce4c20dd7");
INSERT INTO fotos VALUES("21","34","5ee7cf6893387");
INSERT INTO fotos VALUES("22","34","5ee7cf823c708");
INSERT INTO fotos VALUES("23","33","5ee7d03895a88");
INSERT INTO fotos VALUES("24","32","5ee7d28f2bb21");
INSERT INTO fotos VALUES("25","32","5ee7d29e05e87");
INSERT INTO fotos VALUES("26","25","5ee7d364abd30");



DROP TABLE lista_categorias;

CREATE TABLE `lista_categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO lista_categorias VALUES("2","Postres");
INSERT INTO lista_categorias VALUES("3","Sopa");
INSERT INTO lista_categorias VALUES("4","Pescado");
INSERT INTO lista_categorias VALUES("5","Queso");
INSERT INTO lista_categorias VALUES("6","Tartas");
INSERT INTO lista_categorias VALUES("7","Verduras");
INSERT INTO lista_categorias VALUES("8","Patatas");



DROP TABLE log;

CREATE TABLE `log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO log VALUES("143","2020-06-15 19:20:09","El usuario con email admin@admin.admin ha insertado una categoría");
INSERT INTO log VALUES("144","2020-06-15 19:20:18","El usuario con email admin@admin.admin ha insertado una categoría");
INSERT INTO log VALUES("145","2020-06-15 19:21:37","El usuario con email shrek@cienaga.com ha sido creado");
INSERT INTO log VALUES("146","2020-06-15 19:24:07","El usuario con email m.fa@china.com ha sido creado");
INSERT INTO log VALUES("147","2020-06-15 19:24:07","El usuario con id 14 ha sido verificado");
INSERT INTO log VALUES("148","2020-06-15 19:24:40","El usuario con email admin@admin.admin ha finalizado su sesión");
INSERT INTO log VALUES("149","2020-06-15 19:25:06","El usuario con  email shrek@cienaga.com ha comenzado una nueva sesión");
INSERT INTO log VALUES("150","2020-06-15 19:36:34","El usuario con email shrek@cienaga.com ha insertado una categoría");
INSERT INTO log VALUES("151","2020-06-15 19:36:34","El usuario con email shrek@cienaga.com ha insertado una categoría");
INSERT INTO log VALUES("152","2020-06-15 19:36:34","El usuario con email shrek@cienaga.com ha creado la recetaTarta de zanahoria");
INSERT INTO log VALUES("153","2020-06-15 19:38:07","El usuario con email shrek@cienaga.com ha insertado una foto a la receta con id 33");
INSERT INTO log VALUES("154","2020-06-15 19:38:52","El usuario con email shrek@cienaga.com ha insertado una foto a la receta con id 33");
INSERT INTO log VALUES("155","2020-06-15 19:39:11","El usuario con email shrek@cienaga.com ha finalizado su sesión");
INSERT INTO log VALUES("156","2020-06-15 19:40:06","El usuario con  email admin@admin.admin ha comenzado una nueva sesión");
INSERT INTO log VALUES("157","2020-06-15 19:40:06","El usuario con email admin@admin.admin ha comenzado una sesión de administrador");
INSERT INTO log VALUES("158","2020-06-15 19:40:18","El usuario con email m.fa@china.com ha sido creado");
INSERT INTO log VALUES("159","2020-06-15 19:40:23","El usuario con email admin@admin.admin ha finalizado su sesión");
INSERT INTO log VALUES("160","2020-06-15 19:40:37","El usuario con  email m.fa@china.com ha comenzado una nueva sesión");
INSERT INTO log VALUES("161","2020-06-15 19:42:41","El usuario con email m.fa@china.com ha insertado una categoría");
INSERT INTO log VALUES("162","2020-06-15 19:42:41","El usuario con email m.fa@china.com ha creado la recetaArroz con verduras");
INSERT INTO log VALUES("163","2020-06-15 19:43:36","El usuario con email m.fa@china.com ha insertado una foto a la receta con id 34");
INSERT INTO log VALUES("164","2020-06-15 19:44:02","El usuario con email m.fa@china.com ha insertado una foto a la receta con id 34");
INSERT INTO log VALUES("165","2020-06-15 19:44:15","El usuario con email m.fa@china.com ha hecho una valoración a la receta con id 34");
INSERT INTO log VALUES("166","2020-06-15 19:44:27","El usuario con email m.fa@china.com ha insertado un comentario a la receta con id 34");
INSERT INTO log VALUES("167","2020-06-15 19:44:34","El usuario con email m.fa@china.com ha finalizado su sesión");
INSERT INTO log VALUES("168","2020-06-15 19:44:48","El usuario con  email shrek@cienaga.com ha comenzado una nueva sesión");
INSERT INTO log VALUES("169","2020-06-15 19:45:11","El usuario con email shrek@cienaga.com ha insertado un comentario a la receta con id 34");
INSERT INTO log VALUES("170","2020-06-15 19:45:17","El usuario con email shrek@cienaga.com ha hecho una valoración a la receta con id 34");
INSERT INTO log VALUES("171","2020-06-15 19:46:13","El usuario con email shrek@cienaga.com ha borrado la foto con id19");
INSERT INTO log VALUES("172","2020-06-15 19:47:04","El usuario con email shrek@cienaga.com ha insertado una foto a la receta con id 33");
INSERT INTO log VALUES("173","2020-06-15 19:51:06","El usuario con email shrek@cienaga.com ha hecho una valoración a la receta con id 33");
INSERT INTO log VALUES("174","2020-06-15 19:51:35","El usuario con email shrek@cienaga.com ha insertado un comentario a la receta con id 33");
INSERT INTO log VALUES("175","2020-06-15 19:51:42","El usuario con email shrek@cienaga.com ha finalizado su sesión");
INSERT INTO log VALUES("176","2020-06-15 19:52:18","El usuario con  email admin@admin.admin ha comenzado una nueva sesión");
INSERT INTO log VALUES("177","2020-06-15 19:52:18","El usuario con email admin@admin.admin ha comenzado una sesión de administrador");
INSERT INTO log VALUES("178","2020-06-15 19:52:56","El usuario con email admin@admin.admin ha insertado un comentario a la receta con id 33");
INSERT INTO log VALUES("179","2020-06-15 19:53:14","El usuario con email admin@admin.admin ha insertado un comentario a la receta con id 33");
INSERT INTO log VALUES("180","2020-06-15 19:53:18","El usuario con email admin@admin.admin ha hecho una valoración a la receta con id 33");
INSERT INTO log VALUES("181","2020-06-15 19:53:32","El usuario con email admin@admin.admin ha finalizado su sesión");
INSERT INTO log VALUES("182","2020-06-15 19:54:22","El usuario con  email admin@admin.admin ha comenzado una nueva sesión");
INSERT INTO log VALUES("183","2020-06-15 19:54:22","El usuario con email admin@admin.admin ha comenzado una sesión de administrador");
INSERT INTO log VALUES("191","2020-06-15 19:57:03","El usuario con email admin@admin.admin ha insertado una foto a la receta con id 32");
INSERT INTO log VALUES("192","2020-06-15 19:57:18","El usuario con email admin@admin.admin ha insertado una foto a la receta con id 32");
INSERT INTO log VALUES("193","2020-06-15 20:00:36","El usuario con email admin@admin.admin ha insertado una foto a la receta con id 25");
INSERT INTO log VALUES("194","2020-06-15 20:01:00","El usuario con email admin@admin.admin ha insertado una categoría");
INSERT INTO log VALUES("195","2020-06-15 20:01:12","El usuario con email admin@admin.admin ha actualizado una categoría");
INSERT INTO log VALUES("196","2020-06-15 20:01:12","El usuario con email admin@admin.admin ha modificado la receta con título Tortilla de patatas");
INSERT INTO log VALUES("197","2020-06-15 20:01:19","El usuario con email admin@admin.admin ha hecho una valoración a la receta con id 25");



DROP TABLE recetas;

CREATE TABLE `recetas` (
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` varchar(1000) DEFAULT NULL,
  `ingredientes` varchar(1000) DEFAULT NULL,
  `preparacion` varchar(1000) DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `idautor` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario_refeta_idx` (`idautor`),
  CONSTRAINT `id_usuario_refeta` FOREIGN KEY (`idautor`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO recetas VALUES("Crema de guisantes con menta","      Receta fácil y rápida con explicación detallada paso a paso con fotografías para preparar una deliciosa crema de guisantes con menta                  ","      Guisantes congelados, 350 g\nAgua, 200 ml\nMantequilla, 15 g\nAzúcar, 1 cucharadita (*)\nZumo de limón o vinagre blanco, 1 cucharada (*)\nSalsa de menta, 1 cucharada (**)\nSal, al gusto                 ","      En un cazo ponemos el agua y la mantequilla a calentar con el fuego a tope (12/12).\nCuando rompa a hervir, añadimos los guisantes congelados, el azúcar y el zumo de limón o el vinagre blanco. Esperamos a que recupere el hervor (tardará un par de minutos), bajamos el fuego (6/12) y dejamos cocinar 5 minutos más (si son congelados, conviene consultar las instrucciones de la bolsa sobre el tiempo de cocción porque puede variar en función del tamaño de los guisantes).\nEscurrimos los guisantes pero sin tirar el caldo de cocción. Los trituramos con la batidora, añadimos la cucharada de salsa de menta y caldo hasta obtener la consistencia deseada. Yo suelo añadirlo todo.\nAñadimos sal al gusto, terminamos de triturar y servimos. Si queremos una textura más fina se puede pasar por un colador chino                   ","24","5");
INSERT INTO recetas VALUES("Tortilla de patatas","                        La tortilla de patatas o tortilla española es uno de los platos por excelencia de la gastronomía española. Para hacerla sólo necesitamos tres ingredientes: huevos, patatas y un buen aceite. Gusta a mayores y pequeños y se prepara en casi todas las casas, y como todas las recetas populares cada uno prepara la tortilla de patatas a su manera: unos con cebolla, otros sin cebolla, jugosa, muy hecha…unos caliente recién hecha y otros fría                                                            ","                  5 huevos\n500 g de patatas\n1 cebolla\nSal\nAceite de oliva virgen extra                                                    ","                                        Pelamos y lavamos las patatas, las cortamos en rodajas finas al igual que la cebolla. Ponemos ambas cosas en una sartén y cubrimos de aceite de oliva virgen extra, dejamos que se hagan a fuego medio-suave hasta que comiencen a dorarse. Sabréis que las patatas están hechas cuando comiencen a romperse, con la paleta. Para que la tortilla esté jugosa es importante que las patatas se hagan bien y se confiten, porque no hay nada peor que una tortilla con las patatas medio crudas. Por lo tanto, paciencia con este paso.\nLas sacamos de la sartén y escurrimos bien. Ponemos en un cuenco grande, aparte batimos los huevos y los añadimos a las patatas y a la cebolla, añadimos un poco de sal y mezclamos. Dejamos un par de minutos que se mezclen bien. Aquí hay quien prefiere dejar las patatas enteras y quien prefiere machacarlas un poco con la paleta para que se mezclen bien con el huevo.                                                                ","25","5");
INSERT INTO recetas VALUES("Pesto de albahaca","        Recuerdo, como si fuera ayer, la primera vez que probé el pesto de albahaca. Puede que haga casi 20 años de ello y todavía me acuerdo de dónde lo comí, quién me lo dio a probar y cómo estaba combinado.\nEl pesto de albahaca me resultó maravilloso y caí en sus redes de manera instantánea.\n                        ","      Albahaca fresca, sólo las hojas\n Queso Parmesano\nSal\n                 ","        eparamos las hojas de albahaca del tallo (no lo utilizamos porque amarga y estropea el resultado)\n ara ello, las extendemos sobre una hoja de papel absorbente de cocina, colocamos otra hoja encima y presionamos ligeramente con la palma de la mano, con cuidado de no romper ninguna hoja.\n                       ","32","5");
INSERT INTO recetas VALUES("Tarta de zanahoria","        La primera vez que probé este bizcocho de zanahoria me resultó difícil de creer, ¿un bizcocho de zanahorias?… Pues sí, así es, y está verdaderamente delicioso. Jugad a que vuestros invitados adivinen el componente principal, se sorprenderán como me sucedió a mí… Si además le ponéis una capa de frosting de queso o de chocolate fondant, pasará a convertirse en la tarta de zanahoria más rica del mundo, así que toma nota porque es la receta de bizcocho de zanahoria definitiva y una de las más fáciles.\nLa zanahoria es muy rica en caroteno, eficaz antioxidante con propiedades anticancerígenas. La sabiduría popular la considera muy buena para la vista, cicatrizante intestinal, diurética y astringente. Crudas o cocidas son un excelente alimento. Es de las pocas verduras que incluso pierden muy poco valor cocinada. Incluso algunos de sus componentes alimenticios son más digeribles para nuestro cuerpo que cuando las ingerimos crudas.                        ","      250 g de zanahorias crudas (ya peladas y ralladas)\n200 g de harina\n 7 g de levadura en polvo o polvo de hornear\n125 ml de aceite de girasol (podéis usar de oliva pero le dará un sabor más fuerte al pastel de zanahoria)\n4 huevos.\n200 g de azúcar.                 ","        Echamos los huevos en un bol y los batimos con el azúcar, añadiendo luego la pasta de zanahoria que habíamos preparado ccon anterioridad. Incorporamos la harina y la levadura tamizadas y mezclamos con cuidado.\nEngrasamos con aceite o mantequilla un mole de 24 cm y echamos la masa. Hornear a 185ºC unos 30 minutos o hasta que veamos que al pinchar con un palillo o la punta de cuchillo el centro del bizcocho de zanahoria éste sale limpio.\nEste bizcocho de zanahoria podemos dejarlo tal como está si por ejemplo lo queréis para desayuno o merienda, o si lo queréis preparar para alguna celebración podéis espolvorear con azúcar glass o como hemos hecho nosotros arriba con un frosting hecho con Philadelphia y azúcar glass.                        ","33","13");
INSERT INTO recetas VALUES("Arroz con verduras","         En mi día a día suelo comer recetas muy sencillas, de hecho cada vez cocino más simple y dejo las recetas más elaboradas para ocasiones especiales o para los fines de semana\nCuando hago una receta de arroz por lo general suelo cocinarlo aparte y al final lo mezclo con el resto de ingredientes para ahorrar tiempo, porque puedo aprovechar para preparar las dos cosas a la vez y por lo general el arroz tarda menos en estar listo.\n Esta receta es ideal para comidas familiares o con amigos porque es un plato muy sabroso, aunque sigue siendo una receta muy fácil. No tienes por qué usar los mismos ingredientes, yo suelo variar y aprovecho lo que tengo en la cocina, lo que me apetece ese día o lo que está de temporada y la verdad es que siempre sale riquísimo. ¡Es una receta muy resultona!                       ","      1/2 taza de arroz integral (100 g)\nAceite de oliva virgen extra al gusto\n4 dientes de ajo laminados\n1/2 pimiento rojo en tiras  1 tomate troceado             ","        Cocina el arroz siguiendo las instrucciones del paquete (yo usé 1 taza de agua ó 250 ml y estuvo listo en 25 minutos, aunque el agua y el tiempo de cocción varían en función de la marca).\n En una paellera, sartén profunda u olla echa el aceite y cuando esté caliente echa los dientes de ajo y el pimiento. Cocina a fuego medio alto unos 5 minutos, removiendo de vez en cuando.\nEcha las judías, las habas y las alcachofas. Cocina durante unos 10 minutos más.\nAñade el tomate (yo no le quito la piel) y cocina durante 10 minutos.                       ","34","14");



DROP TABLE usuarios;

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL,
  `apellidos` varchar(300) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `foto_perfil_src` varchar(200) DEFAULT NULL,
  `clave1` varchar(100) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `vkey` varchar(100) DEFAULT NULL,
  `verificado` tinyint DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO usuarios VALUES("5","Admin","Admin","admin@admin.admin","5ee28d80af49a","$2y$10$nxZCecmTcANmO43SHxa3jee/pmf.U5gu8ekkZ18rtf/UqFF9.xZ7S","admin","","1");
INSERT INTO usuarios VALUES("13","Shrek","Ogro","shrek@cienaga.com","5ee7ca3f7db1b","$2y$10$ANQs5NvM9qeG0.vHjtzhNOJA2HhGhIUaLiGC9.CBrBnI38bnvEPlu","colaborador","","1");
INSERT INTO usuarios VALUES("14","Mulán","Fa","m.fa@china.com","5ee7cad5845e6","$2y$10$Uw/6hOjM1BVLQ/SIjFPOzucjJKEi6jgvw/E9TOAf6JQLcu4plKdj.","colaborador","","1");



DROP TABLE valoraciones;

CREATE TABLE `valoraciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_receta` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `valoracion` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valoracion_receta_idx` (`id_receta`),
  KEY `id_usuario_idx` (`id_usuario`),
  CONSTRAINT `id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `valoracion_receta` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO valoraciones VALUES("44","34","14","5");
INSERT INTO valoraciones VALUES("45","34","13","4");
INSERT INTO valoraciones VALUES("46","33","13","3");
INSERT INTO valoraciones VALUES("47","33","5","5");
INSERT INTO valoraciones VALUES("48","25","5","5");
