DROP DATABASE IF EXISTS juego;
CREATE DATABASE juego;
USE juego;

CREATE TABLE rol (
                     id smallint auto_increment primary key,
                     descripcion varchar(60)
);

CREATE TABLE nivel(
                      id smallint auto_increment primary key,
                      descripcion varchar(30)
);

CREATE TABLE usuario (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         nombre VARCHAR(255) NOT NULL,
                         fecha_nacimiento DATE NOT NULL,
                         sexo VARCHAR(20) NOT NULL,
                         pais VARCHAR(100) NOT NULL,
                         ciudad VARCHAR(100) NOT NULL,
                         email VARCHAR(255) NOT NULL,
                         password VARCHAR(255) NOT NULL,
                         username VARCHAR(255) NOT NULL UNIQUE,
                         img_profile VARCHAR(255) DEFAULT 'user.webp',
                         fecha_ingreso DATE,
                         codigo_validacion varchar(10),
                         esta_validado boolean,
                         id_rol smallint,
                         id_nivel smallint,
                         puntaje_final int,
                         aciertos int,
                         cant_preguntas int,
                         porcentaje_aciertos float,
                         QR_code varchar(255),
                         FOREIGN KEY (id_rol) REFERENCES rol(id)
);

CREATE TABLE partida(
                        id smallint auto_increment primary key,
                        puntaje int,
                        fecha date,
                        id_usuario int,
                        foreign key (id_usuario) references usuario(id)
);

CREATE TABLE categoria(
                          id smallint auto_increment PRIMARY KEY,
                          descripcion varchar(50),
                          icon varchar(100),
                          color varchar(10)
);

CREATE TABLE pregunta(
                         id int auto_increment PRIMARY KEY,
                         id_cat smallint,
                         id_nivel smallint,
                         descripcion text,
                         aciertos int,
                         cant_presentaciones int,
                         porcentaje_aciertos float,
                         aprobada boolean,
                         reportada boolean,
                         sugerida boolean,
                         fecha date,
                         FOREIGN KEY (id_cat) references categoria(id)
);

CREATE TABLE opcion(
                       id int auto_increment PRIMARY KEY ,
                       pregunta_id int,
                       descripcion text,
                       es_correcta boolean,
                       FOREIGN KEY (pregunta_id) REFERENCES pregunta(id)
);

CREATE TABLE preguntas_contestadas(
                                      id_usuario int,
                                      id_pregunta int,
                                      PRIMARY KEY (id_pregunta,id_usuario),
                                      FOREIGN KEY (id_usuario) REFERENCES usuario(id),
                                      FOREIGN KEY (id_pregunta) REFERENCES pregunta(id)
);

CREATE TABLE duelo(
                      id smallint auto_increment primary key,
                      id_retador int,
                      id_rival int,
                      ganador varchar(100),
                      puntaje_retador int,
                      puntaje_rival int,
                      aceptado boolean,
                      fecha date,
                      FOREIGN KEY (id_retador) REFERENCES usuario(id),
                      FOREIGN KEY (id_rival) REFERENCES usuario(id)
);

CREATE TABLE preguntas_duelo(
                                id_usuario int,
                                id_pregunta int,
                                PRIMARY KEY (id_pregunta,id_usuario),
                                FOREIGN KEY (id_usuario) REFERENCES usuario(id),
                                FOREIGN KEY (id_pregunta) REFERENCES pregunta(id)
);

INSERT INTO rol(descripcion) VALUES('admin'), ('editor'), ('jugador');

INSERT INTO nivel(descripcion) VALUES ('Facil'), ('Medio'),('Dificil');

INSERT INTO usuario(nombre, fecha_nacimiento,sexo,pais,ciudad,email,password,username,img_profile,fecha_ingreso,esta_validado,id_rol,id_nivel, puntaje_final, aciertos, cant_preguntas, porcentaje_aciertos, QR_code)
VALUES ('Laura','2004-03-23', 'Femenino', 'Argentina', 'Buenos Aires', 'admin@gmail.com','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'admin','lionel-messi-copa-mundial-fifa-11266.webp','2018-12-09',true,1 ,1,0,0,0,0.0,'admin.png'),
       ('Paolo','2022-09-23','Masculino','Brasil','Buenos Aires','paoloaleman86@gmail.com','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','Rayzal','davo.jpg','2023-06-18',true,2,1,0,0,0,0.0,'Rayzal.png');


INSERT INTO categoria(descripcion,icon, color) VALUES
                                                   ('Deporte','kirbyDeporte.png', 'FFAC4B' ),
                                                   ('Historia','kirbyHistoria.png', 'FFED4B'),
                                                   ('Arte','kirbyArte.png', 'FF8E8E' ),
                                                   ('Interes General','kirbyCultura.png', '48F4FF' ),
                                                   ('Ciencia','kirbyCiencia.png', '68FF65' );


-- Insertar preguntas
INSERT INTO pregunta (id_cat, id_nivel, descripcion, aciertos, cant_presentaciones,porcentaje_aciertos,aprobada,reportada,sugerida,fecha) VALUES
                                                                                                                                              (1,1,'¿Cuál de los siguientes deportes se juega con una raqueta?',0,0,0.0,true,false,false,'2012-04-10'),
                                                                                                                                              (1,2,'¿Cuál de los siguientes eventos deportivos se celebra cada cuatro años?',0,0,0.0,true,false,false,'2015-07-10'),
                                                                                                                                              (1,3,'¿Cuál de los siguientes equipos es conocido como "Los Lakers"?',0,0,0.0,true,false,false,'2022-04-19'),
                                                                                                                                              (1, 2, '¿Cuál de los siguientes países ha ganado más Copas Mundiales de Fútbol?', 0, 0, 0.0,true,false,false,'2005-01-30'),
                                                                                                                                              (1, 1, '¿Cuál de los siguientes deportes se juega en un campo de golf?', 0, 0, 0.0,true,false,false,'2000-04-13'),
                                                                                                                                              (1, 2, '¿Cuál de los siguientes nadadores ha ganado más medallas olímpicas en la historia?', 0, 0, 0.0,true,false,false,'2022-01-01'),
                                                                                                                                              (1, 3, '¿Cuál de los siguientes equipos no pertenece a la Liga Española de Fútbol?', 0, 0, 0.0,true,false,false,'2021-03-30'),
                                                                                                                                              (1, 1, '¿Cuál de los siguientes deportes se juega en una pista de hielo?', 0, 0, 0.0,true,false,false,'2014-12-20'),
                                                                                                                                              (1, 2, '¿Cuál de los siguientes jugadores de baloncesto es conocido como "Air Jordan"?', 0, 0, 0.0,true,false,false,'2023-01-20'),
                                                                                                                                              (1, 3, '¿Cuál de los siguientes deportes es conocido como "El deporte rey"?', 0, 0, 0.0,true,false,false,'2013-05-14'),


-- Preguntas de Historia

                                                                                                                                              (2, 3, '¿En qué año ocurrió la Revolución Francesa?', 0, 0, 0.0,true,false,false,'1999-09-12'),
                                                                                                                                              (2, 2, '¿Quién fue el primer presidente de los Estados Unidos?', 0, 0, 0.0,true,false,false,'2019-02-19'),
                                                                                                                                              (2, 1, '¿En qué año se firmó el Tratado de Versalles?', 0, 0, 0.0,true,false,false,'2006-11-13'),
                                                                                                                                              (2, 2, '¿Qué líder dirigió la Revolución Rusa de 1917?', 0, 0, 0.0,true,false,false,'2002-06-12'),
                                                                                                                                              (2, 2, '¿Cuál fue la causa principal de la caída del Imperio Romano de Occidente?', 0, 0, 0.0,true,false,false,'2021-01-14'),
                                                                                                                                              (2, 3, '¿En qué año se descubrió América por parte de Cristóbal Colón?', 0, 0, 0.0,true,false,false,'2020-10-18'),
                                                                                                                                              (2, 1, '¿Cuál fue la batalla decisiva en la Segunda Guerra Mundial?', 0, 0, 0.0,true,false,false,'2019-02-12'),
                                                                                                                                              (2, 3, '¿Qué imperio fue conocido por su código legal llamado "Código de Hammurabi"?', 0, 0, 0.0,true,false,false,'2004-09-23'),
                                                                                                                                              (2, 2, '¿Cuál fue la primera civilización en desarrollar la escritura?', 0, 0, 0.0,true,false,false,'2015-08-09'),
                                                                                                                                              (2, 2, '¿Quién fue el líder principal de la independencia de la India?', 0, 0, 0.0,true,false,false,'2022-09-20'),


                                                                                                                                              -- Preguntas de Arte

                                                                                                                                              (3, 3, '¿Quién pintó la Mona Lisa?', 0, 0, 0.0,true,false,false,'2012-03-10'),
                                                                                                                                              (3, 2, '¿Cuál de las siguientes obras es de Vincent van Gogh?', 0, 0, 0.0,true,false,false,'2010-04-10'),
                                                                                                                                              (3, 1, '¿Quién es el autor de la escultura "David"?', 0, 0, 0.0,true,false,false,'2011-08-10'),
                                                                                                                                              (3, 1, '¿En qué período artístico se encuentra la obra "La noche estrellada"?', 0, 0, 0.0,true,false,false,'2023-03-10'),
                                                                                                                                              (3, 2, '¿Cuál de los siguientes artistas es conocido por sus obras en estilo cubista?', 0, 0, 0.0,true,false,false,'2018-03-10'),
                                                                                                                                              (3, 3, '¿Quién escribió la obra de teatro "Romeo y Julieta"?', 0, 0, 0.0,true,false,false,'2022-02-10'),
                                                                                                                                              (3, 1, '¿Cuál de las siguientes películas fue dirigida por Quentin Tarantino?', 0, 0, 0.0,true,false,false,'2015-08-10'),
                                                                                                                                              (3, 2, '¿Quién compuso la famosa sinfonía "La Novena"?', 0, 0, 0.0,true,false,false,'2020-04-09'),
                                                                                                                                              (3, 3, '¿Cuál de las siguientes bandas es considerada pionera del movimiento de rock and roll?', 0, 0, 0.0,true,false,false,'2013-09-10'),
                                                                                                                                              (3, 1, '¿Cuál de los siguientes escritores es conocido por su obra "1984"?', 0, 0, 0.0,true,false,false,'2023-06-10'),



-- Interes General
                                                                                                                                              (4, 1, '¿Cuál es la capital de Francia?', 0, 0, 0.0,true,false,false,'2012-04-10'),
                                                                                                                                              (4, 1, '¿Quién escribió la novela "Cien años de soledad"?', 0, 0, 0.0,true,false,false,'2012-05-10'),
                                                                                                                                              (4, 3, '¿Cuál es el río más largo del mundo?', 0, 0, 0.0,true,false,false,'2012-06-10'),
                                                                                                                                              (4, 2, '¿En qué año se produjo la caída del Muro de Berlín?', 0, 0, 0.0,true,false,false,'2012-02-10'),
                                                                                                                                              (4, 1, '¿Cuál es el metal más abundante en la corteza terrestre?', 0, 0, 0.0,true,false,false,'2012-08-10'),
                                                                                                                                              (4, 1, '¿Cuál es el país más poblado del mundo?', 0, 0, 0.0,true,false,false,'2012-05-10'),
                                                                                                                                              (4, 1, '¿Quién no estuvo presente en la Conferencia de Yalta?', 0, 0, 0.0,true,false,false,'2012-04-10'),
                                                                                                                                              (4, 3, '¿Cuál es el elemento químico con el símbolo "Fe"?', 0, 0, 0.0,true,false,false,'2012-06-10'),
                                                                                                                                              (4, 3, '¿En qué año se celebraron los primeros Juegos Olímpicos modernos?', 0, 0, 0.0,true,false,false,'2012-07-10'),
                                                                                                                                              (4, 2, '¿Cuál es el océano más grande del mundo?', 0, 0, 0.0,true,false,false,'2012-05-10'),

                                                                                                                                              -- Ciencia
                                                                                                                                              (5, 3, '¿Cuál es la unidad básica de la vida?', 0, 0, 0.0,true,false,false,'2023-02-10'),
                                                                                                                                              (5, 2, '¿Cuál es el planeta más grande del sistema solar?', 0, 0, 0.0,true,false,false,'2023-02-10'),
                                                                                                                                              (5, 3, '¿Qué tipo de energía se obtiene a partir de la radiación solar?', 0, 0, 0.0,true,false,false,'2023-10-10'),
                                                                                                                                              (5, 1, '¿Qué órgano es responsable de bombear la sangre en el cuerpo humano?', 0, 0, 0.0,true,false,false,'2023-10-10'),
                                                                                                                                              (5, 2, '¿Cuál es la partícula subatómica con carga positiva?', 0, 0, 0.0,true,false,false,'2023-12-10'),
                                                                                                                                              (5, 1, '¿Cuál es el proceso mediante el cual las plantas convierten la luz solar en energía química?', 0, 0, 0.0,true,false,false,'2023-10-10'),
                                                                                                                                              (5, 1, '¿Cuál es la velocidad de la luz en el vacío?', 0, 0, 0.0,true,false,false,'2023-10-10'),
                                                                                                                                              (5, 1, '¿Qué científico formuló la teoría de la relatividad?', 0, 0, 0.0,true,false,false,'2023-02-10'),
                                                                                                                                              (5, 2, '¿Cuál es el ácido presente en los limones?', 0, 0, 0.0,true,false,false,'2023-06-10'),
                                                                                                                                              (5, 3, '¿Qué tipo de energía se libera en una reacción nuclear?', 0, 0, 0.0,true,false,false,'2023-03-10'),

                                                                                                                                              -- Deportes

                                                                                                                                              (1,1,'¿Quién es el máximo goleador en la historia de la Copa del Mundo de fútbol?',0,0,0.0,true,false,false,'2022-10-10'),
                                                                                                                                              (1,3,'¿Cuál es el único equipo de la NFL que logró una temporada invicta y ganar el Super Bowl en el mismo año?',0,0,0.0,true,false,false,'2022-11-10'),
                                                                                                                                              (1,1,'¿Cuántas veces ha ganado Roger Federer el torneo de Wimbledon en la categoría masculina?',0,0,0.0,true,false,false,'2022-10-10'),
                                                                                                                                              (1,3,'¿Cuál es el único país que ha ganado medallas de oro en los Juegos Olímpicos de verano e invierno?',0,0,0.0,true,false,false,'2022-10-10'),
                                                                                                                                              (1,2,'¿Quién es el piloto más joven en haber ganado un Campeonato Mundial de Fórmula 1?',0,0,0.0,true,false,false,'2022-09-10'),
                                                                                                                                              (1,1,'¿En qué año se celebraron los primeros Juegos Olímpicos de la era moderna?',0,0,0.0,true,false,false,'2022-07-10'),
                                                                                                                                              (1,1,'¿Cuál es el récord mundial de salto de altura en la categoría masculina?',0,0,0.0,true,false,false,'2022-08-10'),
                                                                                                                                              (1,2,'¿Cuál es el único país que ha ganado el Mundial de fútbol en cinco ocasiones?',0,0,0.0,true,false,false,'2022-08-10'),
                                                                                                                                              (1,1,'¿Cuál es el máximo ganador de la Serie Mundial de Béisbol en las Grandes Ligas de Estados Unidos?',0,0,0.0,true,false,false,'2022-06-10'),
                                                                                                                                              (1,2,'¿Cuál es el único golfista en la historia en haber ganado los cuatro torneos de Grand Slam en el mismo año?',0,0,0.0,true,false,false,'2022-03-10'),
                                                                                                                                              (1,3,'¿Cuál es el jugador de baloncesto con más títulos de la NBA en su carrera?',0,0,0.0,true,false,false,'2022-04-10'),
                                                                                                                                              (1,1,'¿Cuál es el récord mundial de velocidad en los 100 metros lisos?',0,0,0.0,true,false,false,'2022-05-10'),
                                                                                                                                              (1,1,'¿Cuál es el equipo de rugby más exitoso de Nueva Zelanda?',0,0,0.0,true,false,false,'2022-02-10'),
                                                                                                                                              (1,3,'¿Cuál es el país más ganador en la historia de los Juegos Olímpicos de Invierno?',0,0,0.0,true,false,false,'2022-06-10'),
                                                                                                                                              (1,1,'¿Cuál es el jugador de tenis con más títulos de Grand Slam en la categoría femenina?',0,0,0.0,true,false,false,'2022-05-10'),
                                                                                                                                              (1,1,'¿Cuál es el equipo de fútbol con más títulos de la Champions League?',0,0,0.0,true,false,false,'2022-03-10'),
                                                                                                                                              (1,2,'¿Quién es el jugador de baloncesto con más puntos anotados en la NBA?',0,0,0.0,true,false,false,'2022-04-10'),
                                                                                                                                              (1,3,'¿Cuál es el récord mundial de salto en longitud en la categoría femenina?',0,0,0.0,true,false,false,'2022-05-10'),
                                                                                                                                              (1,1,'¿Cuál es el equipo de fútbol con más títulos de la Copa Libertadores?',0,0,0.0,true,false,false,'2022-06-10'),
                                                                                                                                              (1,2,'¿Cuál es el jugador de fútbol con más Balones de Oro en su carrera?',0,0,0.0,true,false,false,'2022-05-10');


-- Insertar opciones deportes
INSERT INTO opcion (pregunta_id, descripcion, es_correcta) VALUES
                                                               -- Pregunta 1
                                                               (1, 'Baloncesto', FALSE),
                                                               (1, 'Tenis', TRUE),
                                                               (1, 'Natación', FALSE),
                                                               (1, 'Ciclismo', FALSE),
-- Pregunta 2
                                                               (2, 'Copa Mundial de Fútbol', TRUE),
                                                               (2, 'Copa Libertadores', FALSE),
                                                               (2, 'Campeonato Mundial de Golf', FALSE),
                                                               (2, 'Copa América', FALSE),
-- Pregunta 3
                                                               (3, 'Los Angeles Lakers (baloncesto)', TRUE),
                                                               (3, 'New England Patriots (fútbol americano)', FALSE),
                                                               (3, 'New York Yankees (béisbol)', FALSE),
                                                               (3, 'Real Madrid (fútbol)', FALSE),
-- Pregunta 4
                                                               (4, 'Brasil', TRUE),
                                                               (4, 'Alemania', FALSE),
                                                               (4, 'Argentina', FALSE),
                                                               (4, 'Italia', FALSE),
-- Pregunta 5
                                                               (5, 'Rugby', FALSE),
                                                               (5, 'Vóley playa', FALSE),
                                                               (5, 'Golf', TRUE),
                                                               (5, 'Polo', FALSE),
-- Pregunta 6
                                                               (6, 'Michael Phelps', TRUE),
                                                               (6, 'Usain Bolt', FALSE),
                                                               (6, 'Simone Biles', FALSE),
                                                               (6, 'Roger Federer', FALSE),
-- Pregunta 7
                                                               (7,'Real Madrid', FALSE),
                                                               (7,'Barcelona', FALSE),
                                                               (7, 'Bayern Munich', TRUE),
                                                               (7, 'Atletico Madrid', FALSE),
-- Pregunta 8
                                                               (8, 'Fútbol', FALSE),
                                                               (8, 'Tenis', FALSE),
                                                               (8, 'Hockey sobre hielo', TRUE),
                                                               (8, 'Baloncesto', FALSE),
-- Pregunta 9
                                                               (9, 'Magic Johnson', FALSE),
                                                               (9, 'Kobe Bryant', FALSE),
                                                               (9, 'Larry Bird', FALSE),
                                                               (9, 'Michael Jordan', TRUE),
-- Pregunta 10
                                                               (10, 'Fútbol', TRUE),
                                                               (10, 'Baloncesto', FALSE),
                                                               (10, 'Tenis', FALSE),
                                                               (10, 'Golf', FALSE),

-- Insertar opciones Historia
-- Pregunta 11
                                                               (11, '1789', TRUE),
                                                               (11, '1799', FALSE),
                                                               (11, '1801', FALSE),
                                                               (11, '1815', FALSE),
-- Pregunta 12
                                                               (12, 'George Washington', TRUE),
                                                               (12, 'Abraham Lincoln', FALSE),
                                                               (12, 'Thomas Jefferson', FALSE),
                                                               (12, 'John F. Kennedy', FALSE),
-- Pregunta 13
                                                               (13, '1921', FALSE),
                                                               (13, '1919', TRUE),
                                                               (13, '1933', FALSE),
                                                               (13, '1945', FALSE),
-- Pregunta 14
                                                               (14, 'Joseph Stalin', FALSE),
                                                               (14, 'Leon Trotsky', FALSE),
                                                               (14, 'Vladimir Lenin', TRUE),
                                                               (14, 'Mikhail Gorbachev', FALSE),
-- Pregunta 15
                                                               (15, 'Peste negra', FALSE),
                                                               (15, 'Corrupción política', FALSE),
                                                               (15, 'Invasiones bárbaras', TRUE),
                                                               (15, 'Declive económico', FALSE),
-- Pregunta 16
                                                               (16, '1501', FALSE),
                                                               (16, '1492', TRUE),
                                                               (16, '1510', FALSE),
                                                               (16, '1520', FALSE),
-- Pregunta 17
                                                               (17, 'Batalla de Stalingrado', TRUE),
                                                               (17, 'Batalla de Normandía', FALSE),
                                                               (17, 'Batalla de Midway', FALSE),
                                                               (17, 'Batalla de Iwo Jima', FALSE),
-- Pregunta 18
                                                               (18, 'Imperio Persa', FALSE),
                                                               (18, 'Imperio Romano', FALSE),
                                                               (18, 'Imperio Egipcio', FALSE),
                                                               (18, 'Imperio Babilónico', TRUE),
-- Pregunta 19
                                                               (19, 'Egipcios', FALSE),
                                                               (19, 'Mayas', FALSE),
                                                               (19, 'Sumerios', TRUE),
                                                               (19, 'Romanos', FALSE),
-- Pregunta 20
                                                               (20, 'Jawaharlal Nehru', FALSE),
                                                               (20, 'Mahatma Gandhi', TRUE),
                                                               (20, 'Indira Gandhi', FALSE),
                                                               (20, 'Rajiv Gandhi', FALSE),

-- Insertar opciones Arte
-- Pregunta 21
                                                               (21, 'Leonardo da Vinci', TRUE),
                                                               (21, 'Pablo Picasso', FALSE),
                                                               (21, 'Salvador Dalí', FALSE),
                                                               (21, 'Rembrandt', FALSE),

-- Pregunta 22
                                                               (22, 'La noche estrellada', FALSE),
                                                               (22, 'El grito', FALSE),
                                                               (22, 'Los girasoles', TRUE),
                                                               (22, 'La persistencia de la memoria', FALSE),

-- Pregunta 23
                                                               (23, 'Leone Leoni', FALSE),
                                                               (23, 'Auguste Rodin', FALSE),
                                                               (23, 'Donatello', FALSE),
                                                               (23, 'Michelangelo Buonarroti', TRUE),

-- Pregunta 24
                                                               (24, 'Impresionismo', FALSE),
                                                               (24, 'Renacimiento', FALSE),
                                                               (24, 'Expresionismo', FALSE),
                                                               (24, 'Postimpresionismo', TRUE),

-- Pregunta 25
                                                               (25, 'Pablo Picasso', TRUE),
                                                               (25, 'Claude Monet', FALSE),
                                                               (25, 'Salvador Dalí', FALSE),
                                                               (25, 'Vincent van Gogh', FALSE),

-- Pregunta 26
                                                               (26, 'Oscar Wilde', FALSE),
                                                               (26, 'Friedrich Nietzsche', FALSE),
                                                               (26, 'William Shakespeare', TRUE),
                                                               (26, 'Miguel de Cervantes', FALSE),

-- Pregunta 27
                                                               (27, 'Pulp Fiction', TRUE),
                                                               (27, 'El Padrino', FALSE),
                                                               (27, 'Cadena perpetua', FALSE),
                                                               (27, 'El club de la pelea', FALSE),

-- Pregunta 28
                                                               (28, 'Ludwig van Beethoven', TRUE),
                                                               (28, 'Wolfgang Amadeus Mozart', FALSE),
                                                               (28, 'Johann Sebastian Bach', FALSE),
                                                               (28, 'Franz Schubert', FALSE),

-- Pregunta 29
                                                               (29, 'The Rolling Stones', FALSE),
                                                               (29, 'The Beatles', TRUE),
                                                               (29, 'Led Zeppelin', FALSE),
                                                               (29, 'Pink Floyd', FALSE),

-- Pregunta 30
                                                               (30, 'Aldous Huxley', FALSE),
                                                               (30, 'Ray Bradbury', FALSE),
                                                               (30, 'H.G. Wells', FALSE),
                                                               (30, 'George Orwell', TRUE),

-- Pregunta 31
                                                               (31, 'Madrid', FALSE),
                                                               (31, 'París', TRUE),
                                                               (31, 'Londres', FALSE),
                                                               (31, 'Roma', FALSE),

-- Pregunta 32
                                                               (32, 'Julio Cortázar', FALSE),
                                                               (32, 'Mario Vargas Llosa', FALSE),
                                                               (32, 'Gabriel García Márquez', TRUE),
                                                               (32, 'Isabel Allende', FALSE),

-- Pregunta 33
                                                               (33, 'Amazonas', TRUE),
                                                               (33, 'Nilo', FALSE),
                                                               (33, 'Misisipi', FALSE),
                                                               (33, 'Yangtsé', FALSE),

-- Pregunta 34
                                                               (34, '1989', TRUE),
                                                               (34, '1991', FALSE),
                                                               (34, '1993', FALSE),
                                                               (34, '1987', FALSE),

-- Pregunta 35
                                                               (35, 'Hierro', FALSE),
                                                               (35, 'Aluminio', TRUE),
                                                               (35, 'Cobre', FALSE),
                                                               (35, 'Silicio', FALSE),

-- Pregunta 36
                                                               (36, 'China', FALSE),
                                                               (36, 'India', TRUE),
                                                               (36, 'Estados Unidos', FALSE),
                                                               (36, 'Brasil', FALSE),

-- Pregunta 37
                                                               (37, 'Winston Churchill', FALSE),
                                                               (37, 'Franklin D. Roosevelt', FALSE),
                                                               (37, 'Joseph Stalin', FALSE),
                                                               (37, 'Adolf Hitler ', TRUE),

-- Pregunta 38
                                                               (38, 'Hierro', TRUE),
                                                               (38, 'Calcio', FALSE),
                                                               (38, 'Plata', FALSE),
                                                               (38, 'Oro', FALSE),

-- Pregunta 39
                                                               (39, '1896', TRUE),
                                                               (39, '1900', FALSE),
                                                               (39, '1920', FALSE),
                                                               (39, '1936', FALSE),

-- Pregunta 40
                                                               (40, 'Océano Pacífico', FALSE),
                                                               (40, 'Océano Atlántico', FALSE),
                                                               (40, 'Océano Índico', FALSE),
                                                               (40, 'Océano Antártico', TRUE),

-- Insertar opciones restantes
-- Pregunta 41
                                                               (41, 'Célula', TRUE),
                                                               (41, 'Átomo', FALSE),
                                                               (41, 'Molécula', FALSE),
                                                               (41, 'Electrón', FALSE),

-- Pregunta 42
                                                               (42, 'Júpiter', TRUE),
                                                               (42, 'Marte', FALSE),
                                                               (42, 'Venus', FALSE),
                                                               (42, 'Saturno', FALSE),

-- Pregunta 43
                                                               (43, 'Energía solar', TRUE),
                                                               (43, 'Energía eólica', FALSE),
                                                               (43, 'Energía geotérmica', FALSE),
                                                               (43, 'Energía hidroeléctrica', FALSE),

-- Pregunta 44
                                                               (44, 'Corazón', TRUE),
                                                               (44, 'Cerebro', FALSE),
                                                               (44, 'Pulmón', FALSE),
                                                               (44, 'Hígado', FALSE),

-- Pregunta 45
                                                               (45, 'Protón', TRUE),
                                                               (45, 'Electrón', FALSE),
                                                               (45, 'Neutrón', FALSE),
                                                               (45, 'Fotón', FALSE),

-- Pregunta 46
                                                               (46, 'Fotosíntesis', TRUE),
                                                               (46, 'Respiración celular', FALSE),
                                                               (46, 'Fisión nuclear', FALSE),
                                                               (46, 'Fusión nuclear', FALSE),

-- Pregunta 47
                                                               (47, '299,792,458 metros por segundo', TRUE),
                                                               (47, '30,000 kilómetros por hora', FALSE),
                                                               (47, '3,000,000 kilómetros por segundo', FALSE),
                                                               (47, '30 centímetros por nanosegundo', FALSE),

-- Pregunta 48
                                                               (48, 'Albert Einstein', TRUE),
                                                               (48, 'Isaac Newton', FALSE),
                                                               (48, 'Stephen Hawking', FALSE),
                                                               (48, 'Marie Curie', FALSE),

-- Pregunta 49
                                                               (49, 'Ácido cítrico', TRUE),
                                                               (49, 'Ácido acético', FALSE),
                                                               (49, 'Ácido sulfúrico', FALSE),
                                                               (49, 'Ácido clorhídrico', FALSE),

-- Pregunta 50
                                                               (50, 'Energía nuclear', TRUE),
                                                               (50, 'Energía térmica', FALSE),
                                                               (50, 'Energía cinética', FALSE),
                                                               (50, 'Energía química', FALSE),

-- Pregunta 51
                                                               (51, 'Pelé', FALSE),
                                                               (51, 'Cristiano Ronaldo', FALSE),
                                                               (51, 'Lionel Messi', FALSE),
                                                               (51, 'Miroslav Klose', TRUE),

-- Pregunta 52
                                                               (52, 'New England Patriots', TRUE),
                                                               (52, 'Pittsburgh Steelers', FALSE),
                                                               (52, 'Dallas Cowboys', FALSE),
                                                               (52, 'San Francisco 49ers', FALSE),

-- Pregunta 53
                                                               (53, '6', FALSE),
                                                               (53, '7', FALSE),
                                                               (53, '8', TRUE),
                                                               (53, '9', FALSE),

-- Pregunta 54
                                                               (54, 'Estados Unidos', TRUE),
                                                               (54, 'Rusia', FALSE),
                                                               (54, 'China', FALSE),
                                                               (54, 'Alemania', FALSE),

-- Pregunta 55
                                                               (55, 'Sebastian Vettel', FALSE),
                                                               (55, 'Ayrton Senna', FALSE),
                                                               (55, 'Max Verstappen', FALSE),
                                                               (55, 'Lewis Hamilton', TRUE),

-- Pregunta 56
                                                               (56, '1896', TRUE),
                                                               (56, '1900', FALSE),
                                                               (56, '1920', FALSE),
                                                               (56, '1936', FALSE),

-- Pregunta 57
                                                               (57, '2.43 metros', FALSE),
                                                               (57, '2.38 metros', FALSE),
                                                               (57, '2.45 metros', TRUE),
                                                               (57, '2.50 metros', FALSE),

-- Pregunta 58
                                                               (58, 'Brasil', TRUE),
                                                               (58, 'Alemania', FALSE),
                                                               (58, 'Italia', FALSE),
                                                               (58, 'Argentina', FALSE),

-- Pregunta 59
                                                               (59, 'New York Yankees', TRUE),
                                                               (59, 'Boston Red Sox', FALSE),
                                                               (59, 'Los Angeles Dodgers', FALSE),
                                                               (59, 'San Francisco Giants', FALSE),

-- Pregunta 60
                                                               (60, 'Jack Nicklaus', TRUE),
                                                               (60, 'Arnold Palmer', FALSE),
                                                               (60, 'Tiger Woods', FALSE),
                                                               (60, 'Rory McIlroy', FALSE),

-- Pregunta 61
                                                               (61, 'Bill Russell', FALSE),
                                                               (61, 'Kareem Abdul-Jabbar', TRUE),
                                                               (61, 'Michael Jordan', FALSE),
                                                               (61, 'LeBron James', FALSE),

-- Pregunta 62
                                                               (62, '9.58 segundos', TRUE),
                                                               (62, '9.74 segundos', FALSE),
                                                               (62, '9.86 segundos', FALSE),
                                                               (62, '9.92 segundos', FALSE),

-- Pregunta 63
                                                               (63, 'All Blacks', TRUE),
                                                               (63, 'Wallabies', FALSE),
                                                               (63, 'Springboks', FALSE),
                                                               (63, 'Lions', FALSE),

-- Pregunta 64
                                                               (64, 'Noruega', FALSE),
                                                               (64, 'Estados Unidos', FALSE),
                                                               (64, 'Alemania', FALSE),
                                                               (64, 'Rusia', TRUE),

-- Pregunta 65
                                                               (65, 'Serena Williams', TRUE),
                                                               (65, 'Steffi Graf', FALSE),
                                                               (65, 'Martina Navratilova', FALSE),
                                                               (65, 'Chris Evert', FALSE),

-- Pregunta 66
                                                               (66, 'Real Madrid', TRUE),
                                                               (66, 'FC Barcelona', FALSE),
                                                               (66, 'AC Milan', FALSE),
                                                               (66, 'Bayern Múnich', FALSE),

-- Pregunta 67
                                                               (67, 'Kareem Abdul-Jabbar', TRUE),
                                                               (67, 'Karl Malone', FALSE),
                                                               (67, 'LeBron James', FALSE),
                                                               (67, 'Michael Jordan', FALSE),

-- Pregunta 68
                                                               (68, '7.52 metros', FALSE),
                                                               (68, '7.63 metros', TRUE),
                                                               (68, '7.38 metros', FALSE),
                                                               (68, '7.25 metros', FALSE),

-- Pregunta 69
                                                               (69, 'Club Atlético Independiente', TRUE),
                                                               (69, 'Boca Juniors', FALSE),
                                                               (69, 'River Plate', FALSE),
                                                               (69, 'Peñarol', FALSE),

-- Pregunta 70
                                                               (70, 'Lionel Messi', TRUE),
                                                               (70, 'Cristiano Ronaldo', FALSE),
                                                               (70, 'Diego Maradona', FALSE),
                                                               (70, 'Pelé', FALSE);