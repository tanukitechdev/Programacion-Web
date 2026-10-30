/* Espinoza Sánchez Luis Miguel
Programación WEB 25 A
*/

/* Tabla USUARIOS */

CREATE TABLE usuarios(
id_usuario int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nombre varchar(100) NOT NULL,
email varchar(100) UNIQUE NOT NULL,
password varchar(255) NOT NULL,
rol enum('admin','usuario') NOT NULL DEFAULT 'usuario',
fecha_registro timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* Tabla HABITOS */

CREATE TABLE habitos(
id_habito int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
id_usuario int(11) UNSIGNED NOT NULL,
nombre_habito varchar(100) NOT NULL,
descripcion text DEFAULT NULL,
frecuencia enum('diaria','semanal','personalizada') NOT NULL DEFAULT 'diaria',
meta varchar(50) DEFAULT NULL,
fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* Tabla SEGUIMIENTO */

CREATE TABLE seguimiento_habitos(
id_seguimiento int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
id_habito int(11) UNSIGNED NOT NULL,
fecha_seguimiento date NOT NULL,
completado boolean NOT NULL DEFAULT FALSE,
fecha_registro timestamp DEFAULT CURRENT_TIMESTAMP,
UNIQUE KEY uk_habito_fecha (id_habito,fecha_seguimiento),
FOREIGN KEY (id_habito) REFERENCES habitos (id_habito) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* Tabla METAS */

CREATE TABLE metas(
id_meta int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
id_habito int(11) UNSIGNED NOT NULL,
tipo_meta varchar(50) NOT NULL,
valor_meta int(11) NOT NULL,
unidad_meta varchar(50) DEFAULT NULL,
FOREIGN KEY (id_habito) REFERENCES habitos (id_habito) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;