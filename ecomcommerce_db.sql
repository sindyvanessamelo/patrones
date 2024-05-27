CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    descripcion TEXT,
    precio INT,
    cantidad_stock INT,
    categoria VARCHAR(255),
    imagen VARCHAR(255)
);
-----------

INSERT INTO productos (nombre, descripcion, precio, cantidad_stock, categoria, imagen) VALUES
('Lenteja FRESCAMPO 500 gr', 'Gramos seleccionados', 3280, 6, 'mercado', 'lenteja.jpg'),
('Sal Refinada REFISAL 1000 gr', 'Sal Alta Pureza', 2430, 6, 'mercado', 'sal.jpg'),
('Aceite Vegetal FRESCAMPO 3000 ml', 'Mililitros a 6', 19440, 6, 'mercado', 'aceite.jpg'),
('PASTA SPAGUETTI LA MUNECA 1000 gr', 'Gramos a 4', 4980, 6, 'mercado', 'pasta.jpg');


INSERT INTO productos (nombre, descripcion, precio, cantidad_stock, categoria, imagen) VALUES
('Computador ASUS Vivobook Go 15 AMD Ryzen 5', 'Batería 42WHrs, 3S1P, 3-cell Li-ion, Ancho 36.03 cm', 1763958, 6, 'tecnologia', 'laptop.jpg'),
('Tablet LENOVO M11 10.95 Pulgadas', 'Capacidad de almacenamiento 128 GB, Ancho 25.5 cm', 1499900, 6, 'tecnologia', 'tablet.jpg'),
('Alexa Parlante Inteligente Amazon Echo Dot', 'Factor Neto PUM 1.0, Factur Escurrido PUM 1', 203860, 6, 'tecnologia', 'alexa.jpg'),
('Televisor SAMSUNG 58 Pulgadas LED Uhd4K', 'Referencia UNSC87000KXZL, Ancho 129.1 cm', 1899900, 6, 'tecnologia', 'tv.jpg');


INSERT INTO productos (nombre, descripcion, precio, cantidad_stock, categoria, imagen) VALUES
('Lavadora HACEB Carga Superior 8 kg', 'Carga Superior 8 kg', 1200539, 6, 'electrodomesticos', 'lavadora.jpg'),
('Microondas WHIRLPOOL 07 FT Negro', '07 FT Negro', 349900, 6, 'electrodomesticos', 'microondas.jpg'),
('Nevecon SAMSUNG Side By Side 647 L', 'Side By Side 647 L', 4159254, 6, 'electrodomesticos', 'nevera.jpg'),
('Nevecon SAMSUNG Tipo Europeo 788 Litros', 'Tipo Europeo 788 Litros', 4986228, 6, 'electrodomesticos', 'nevera2.jpg');

UPDATE productos SET cantidad_stock = 6;


---------------

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Cambio de nombre de columna
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    nombre VARCHAR(100),
    direccion TEXT,
    tarjeta VARCHAR(20),
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id)
);

CREATE TABLE detalles_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT,
    producto_id INT,
    cantidad INT,
    precio DECIMAL(10, 2),
    FOREIGN KEY (compra_id) REFERENCES compras(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id_producto)
);