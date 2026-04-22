CREATE TABLE branches (
    branch_id INT PRIMARY KEY,  
    branch_name VARCHAR(50) NOT NULL,   
    address VARCHAR(255) NOT NULL,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
);
CREATE TABLE  specializations (
    specialization_id INT PRIMARY KEY AUTO_INCREMENT,/*-- بيضيف البيانات تلقي مع كل اضافة */
    specialization_name  VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP /*-- بيضيف  وقت تاريخ تلقائي  مع كل اضافة */
);

CREATE TABLE  trainers (
    trainer_id INT PRIMARY KEY AUTO_INCREMENT, /* -- يزيد تلقائيًا مع كل مدرب جديد*/
    trainer_name VARCHAR(50) NOT NULL,
    email_id VARCHAR(50) NOT NULL UNIQUE,
    join_date DATE NOT NULL,
    salary DECIMAL(7,2) NOT NULL,
    branch_id INT,
    
    FOREIGN KEY (branch_id) REFERENCES branches(branch_id) ON DELETE SET NULL
    /* -- هو خيار مهم إذا كنت لا ترغب في حذف المدربين عندما يتم حذف الفرع.  سوف تعين قيمه  null  لو حذف  الفرع */
);
CREATE TABLE  trainer_specializations (
    trainer_id INT NOT NULL,
    specialization_id INT NOT NULL,
    PRIMARY KEY (trainer_id, specialization_id),
    FOREIGN KEY (trainer_id) REFERENCES trainers(trainer_id) ON DELETE CASCADE,
    FOREIGN KEY (specialization_id) REFERENCES specializations(specialization_id) ON DELETE CASCADE
);

CREATE TABLE memberships (
    id_plan INT PRIMARY KEY AUTO_INCREMENT,        
    plan_name VARCHAR(50) NOT NULL,                
    price DECIMAL(7,2) NOT NULL,                    
    duration_months INT NOT NULL                   
);

CREATE TABLE  members (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    weight DECIMAL(5,2) NOT NULL CHECK (weight BETWEEN 30 AND 300),
    height DECIMAL(5,2) NOT NULL CHECK (height BETWEEN 100 AND 250),
    gender ENUM('Male', 'Female') NOT NULL,
    phone_no VARCHAR(20) UNIQUE ,
    email_id VARCHAR(100)  UNIQUE,
    branch_id INT NOT NULL,
    trainer_id INT,
    membership_id INT NOT NULL,
     start_date DATE NOT NULL DEFAULT CURRENT_DATE,
    end_date DATE NOT NULL ,

    date_of_birth DATE NOT NULL,--  سن العميل
    
    FOREIGN KEY (trainer_id) REFERENCES trainers(trainer_id) ON DELETE SET NULL,
    FOREIGN KEY (membership_id) REFERENCES memberships(id_plan) ON DELETE RESTRICT,
    FOREIGN KEY (branch_id) REFERENCES branches(branch_id) ON DELETE RESTRICT
);
CREATE TABLE  products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100) NOT NULL,
    category ENUM('Supplement', 'Equipment', 'Clothing', 'Other') NOT NULL,
    price DECIMAL(7,2) NOT NULL CHECK (price > 1),
    stock_quantity INT NOT NULL DEFAULT 0 CHECK (stock_quantity >= 0),
    description TEXT

    
);
/*-- Table: Sales */
CREATE TABLE sales (
    sale_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT DEFAULT NULL,          /* -- جعل member_id قابلاً للـ NULL لأن العضو قد لا يكون هو المشتري*/
    product_id INT NOT NULL,            /*  -- معرف المنتج الذي تم بيعه*/
    quantity INT NOT NULL,               /* -- الكمية */
    price_per_unit DECIMAL(7,2) NOT NULL, /*-- سعر الوحدة*/
    total_price DECIMAL(7,2) AS (quantity * price_per_unit) STORED, /*-- الحساب التلقائي للمبلغ الإجمالي*/
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, /*-- تاريخ البيع*/
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE SET NULL,  /*-- ربط العضو مع احترام ال NULL*/
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT /* -- ربط المنتج*/
);




CREATE TABLE feedback_trainers (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT ,
    trainer_id INT  ,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comments TEXT,
    feedback_date DATE NOT NULL DEFAULT CURRENT_DATE, /*-- تاريخ اليوم تلقائي*/
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(trainer_id) ON DELETE SET NULL
);

CREATE TABLE feedback_branches (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT ,
    branch_id INT , 
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comments TEXT,
    feedback_date DATE NOT NULL DEFAULT CURRENT_DATE, -- ن
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(branch_id) ON DELETE SET NULL
);

INSERT INTO branches (branch_id, branch_name, address) 
VALUES 
(1, 'Giza Branch', 'Giza, Tahrir Street'),
(2, 'Cairo Branch', 'Cairo, Abbas El-Akkad Street'),
(3, 'Alexandria Branch', 'Alexandria, Karmoz Street'),
(4, 'Mansoura Branch', 'Mansoura, Al-Gomhoria Street'),
(5, 'Aswan Branch', 'Aswan, Nile Street');
INSERT INTO specializations ( specialization_name )
VALUES 
('Bodybuilding'),
('Yoga'),
('CrossFit'),
('Aerobics'),
('Pilates');
INSERT INTO trainers (trainer_name, email_id, join_date, salary, branch_id)
VALUES
('Ahmed Ali', 'ahmed@domain.com', '2025-04-06', 5000.00, 1),
('Sara Ibrahim', 'sara@domain.com', '2025-04-01', 4500.00, 2),
('Mohamed Yasser', 'mohamed@domain.com', '2025-03-20', 5500.00, 1),
('Hassan Tamer', 'hassan@domain.com', '2025-02-15', 6000.00, 3),
('Laila Nour', 'laila@domain.com', '2025-01-10', 4700.00, 2);

INSERT INTO trainer_specializations(trainer_id,specialization_id)
VALUES
(1, 1), /* -- Ahmed Ali متخصص في Bodybuilding*/
(3, 5), /* -- Mohamed Yasser متخصص في Pilates*/
(2, 2);  /*-- Sara Ibrahim متخصصة في Yoga */

INSERT INTO memberships (plan_name, price, duration_months)
VALUES ('Gold Plan', 200.00, 12),
       ('Silver Plan', 150.00, 6),
       ('Bronze Plan', 100.00, 3);

/*--دالة `DATE_ADD(CURRENT_DATE, INTERVAL 3 MONTH)` تضيف 3 أشهر إلى التاريخ الحالي (`CURRENT_DATE`) وتعيد التاريخ الجديد بعد إضافة الأشهر.*/
INSERT INTO members (first_name, last_name, weight, height, gender, phone_no, email_id, branch_id, trainer_id, membership_id, start_date, end_date, date_of_birth)
VALUES 
('micel', 'ameeal', 70.0, 175.0, 'Male', '01012345678', 'micel@example.com', 1, 1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), '1990-05-01'),

('ahmed', 'wahied', 80.0, 180.0, 'Male', '01087654321', 'ahmed@example.com', 1, 2, 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 6 MONTH), '2004-06-29'),
('abd elramann', 'emaad', 85.0, 185.0, 'Male', '01122334455', 'abdemaad@example.com', 2, 1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), '1995-09-15'),
('ahmed', 'ihab', 78.0, 178.0, 'Male', '01234567890', 'ahmedihab@example.com', 3, 2, 3,CURDATE(), DATE_ADD(CURDATE(), INTERVAL 12 MONTH), '1997-01-22'),
('yaya', 'aymen', 90.0, 190.0, 'Male', '01554433221', 'yayaaymen@example.com', 1, 3, 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 6 MONTH), '1992-11-30');

INSERT INTO products (product_name, category, price, stock_quantity, description)
 VALUES
('Protein Powder', 'Supplement', 49.99, 100, 'High-quality whey protein powder for muscle recovery and growth.'),
('Yoga Mat', 'Equipment', 19.99, 50, 'Non-slip yoga mat for all types of workouts.'),
('Dumbbell Set', 'Equipment', 89.99, 30, 'Set of adjustable dumbbells for strength training.'),
('Gym T-shirt', 'Clothing', 14.99, 200, 'Comfortable gym t-shirt, breathable fabric for workouts.'),
('Pre-workout Supplement', 'Supplement', 29.99, 75, 'Energy-boosting pre-workout supplement to enhance performance.'),
('Fitness Tracker', 'Other', 59.99, 40, 'Wearable fitness tracker to monitor your daily activities and health metrics.');

/*
-- بيع 10 وحدات من كل منتج
-- مثلا، المنتج الأول: product_id = 1
-- المنتج الثاني: product_id = 2
-- وهكذا...

-- 1. إدخال مبيعات في جدول sales
*/
INSERT INTO sales (product_id, quantity, price_per_unit,member_id)
VALUES 
(1, 10, 49.99,1), 
(2, 10, 19.99,1),  
(3, 10, 89.99,2);  /*-- 

-- 2. تحديث الكميات في جدول products
UPDATE products
SET stock_quantity = stock_quantity - 10
WHERE product_id IN (1, 2, 3);
*/
INSERT INTO feedback_trainers (member_id, trainer_id, rating, comments)
VALUES
(1, 1, 2, 'المدرب مش مركز خالص'),
(2, 2, 1, 'التمرينات مملة جدًا'),
(3, 2, 2, NULL),
(4, 2, 3, 'مدرب ممتاز ومتابع كويس'),
(5, 3, 5, NULL);

INSERT INTO feedback_branches (member_id, branch_id, rating, comments)
VALUES
(1, 1, 1, 'الفرع قديم والمعدات بايظة'),
(2, 2, 2, NULL),
(3, 3, 2, 'الاستقبال مش متعاون'),
(4, 1, 4, 'المكان نضيف جدًا'),
(5, 2, 5, NULL);

