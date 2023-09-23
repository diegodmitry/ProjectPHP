CREATE DATABASE casopratico;

CREATE TABLE Users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  phone VARCHAR(20),
  role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
);
CREATE TABLE News (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  date_published TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Clients (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  phone VARCHAR(20),
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE Projects (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  technology_used VARCHAR(255) NOT NULL,
  completion_date DATE NOT NULL,
  image_url VARCHAR(255) NOT NULL
);

-- Fill tables with data
INSERT INTO Users (username, password, email, phone, role) VALUES
  ('john', '123456', 'john@example.com', '555-1234', 'admin'),
  ('jane', 'abcdef', 'jane@example.com', '555-5678', 'user'),
  ('bob', 'ghijkl', 'bob@example.com', '555-9012', 'user');

INSERT INTO News (title, content, date_published) VALUES
  ('MasterD wins award', 'MasterD has been awarded the Best Online Learning Platform award for the third year in a row.', '2021-05-01 10:00:00'),
  ('New course available', 'We are excited to announce the launch of our new course on Web Development. Enroll now and start learning today!', '2021-06-15 14:30:00'),
  ('MasterD partners with Google', 'We are proud to announce our partnership with Google to offer our students the best learning experience.', '2021-07-20 09:45:00');

INSERT INTO Clients (name, email, phone, username, password) VALUES
  ('Alice Smith', 'alice@example.com', '555-1234', 'alice', 'password'),
  ('Bob Johnson', 'bob@example.com', '555-5678', 'bob', 'password'),
  ('Carol Lee', 'carol@example.com', '555-9012', 'carol', 'password');

INSERT INTO Projects (name, description, technology_used, completion_date, image_url) VALUES
  ('E-commerce website', 'A website for an online store that sells clothing and accessories.', 'HTML, CSS, JS', '2021-03-31', 'https://example.com/images/ecommerce-website.jpg'),
  ('Portfolio website', 'A website to showcase a photographer''s portfolio.', 'HTML, CSS, JS', '2021-05-15', 'https://example.com/images/portfolio-website.jpg'),
  ('Blog website', 'A website for a blogger to share their thoughts and ideas.', 'HTML, CSS, JS', '2021-07-01', 'https://example.com/images/blog-website.jpg');
