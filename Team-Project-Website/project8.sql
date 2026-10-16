/* Team 9 Members: Mauricio Diaz Reyes, Samuel Berebitsky, Madelynn Hedges
   Project Step 8 – Data Population
   I308: Populate Database Tables (Fall 2025)
   */
DROP TABLE
IF
  EXISTS tutor_match;
  DROP TABLE
  IF
    EXISTS request;
    DROP TABLE
    IF
      EXISTS tutor_req;
      DROP TABLE
      IF
        EXISTS tutor;
        DROP TABLE
        IF
          EXISTS course;
          DROP TABLE
          IF
            EXISTS overseer;
            DROP TABLE
            IF
              EXISTS student;

              CREATE TABLE student (
                student_id INT AUTO_INCREMENT
                , first_name VARCHAR(50) NOT NULL
                , last_name VARCHAR(50) NOT NULL
                , email VARCHAR(100) NOT NULL
                , phone VARCHAR(20)
                , school_affiliation VARCHAR(100)
                , PRIMARY KEY (student_id)
              ) ENGINE = INNODB;

              CREATE TABLE tutor (
                tutor_id INT AUTO_INCREMENT
                , first_name VARCHAR(50) NOT NULL
                , last_name VARCHAR(50) NOT NULL
                , email VARCHAR(100) NOT NULL
                , phone VARCHAR(20)
                , experience VARCHAR(500)
                , PRIMARY KEY (tutor_id)
              ) ENGINE = INNODB;

              CREATE TABLE tutor_req (
                tutor_id INT
                , availability VARCHAR(100)
                , format VARCHAR(50)
                , rate_free DECIMAL(10, 2)
                , PRIMARY KEY (tutor_id)
                , FOREIGN KEY (tutor_id) REFERENCES tutor(tutor_id)
              ) ENGINE = INNODB;

              CREATE TABLE course (
                course_id INT AUTO_INCREMENT
                , course_number VARCHAR(20) NOT NULL
                , course_name VARCHAR(100) NOT NULL
                , instructor VARCHAR(100)
                , description VARCHAR(500)
                , PRIMARY KEY (course_id)
              ) ENGINE = INNODB;

              CREATE TABLE request (
                request_id INT AUTO_INCREMENT
                , budget DECIMAL(10, 2)
                , schedule VARCHAR(100)
                , topics VARCHAR(500)
                , format VARCHAR(50)
                , student_id INT NOT NULL
                , course_id INT NOT NULL
                , PRIMARY KEY (request_id)
                , FOREIGN KEY (student_id) REFERENCES student(student_id)
                , FOREIGN KEY (course_id) REFERENCES course(course_id)
              ) ENGINE = INNODB;

              CREATE TABLE overseer (
                overseer_id INT AUTO_INCREMENT
                , first_name VARCHAR(50) NOT NULL
                , last_name VARCHAR(50) NOT NULL
                , email VARCHAR(100) NOT NULL
                , role VARCHAR(50)
                , PRIMARY KEY (overseer_id)
              ) ENGINE = INNODB;

              CREATE TABLE tutor_match (
                match_id INT AUTO_INCREMENT
                , request_id INT NOT NULL
                , tutor_id INT NOT NULL
                , match_status VARCHAR(50)
                , payment_status VARCHAR(50)
                , notes VARCHAR(500)
                , overseer_id INT
                , PRIMARY KEY (match_id)
                , FOREIGN KEY (request_id) REFERENCES request(request_id)
                , FOREIGN KEY (tutor_id) REFERENCES tutor(tutor_id)
                , FOREIGN KEY (overseer_id) REFERENCES overseer(overseer_id)
              ) ENGINE = INNODB;


              --Populate Tables

              INSERT INTO
                student (
                  first_name
                  , last_name
                  , email
                  , phone
                  , school_affiliation
                )
              VALUES
                (
                  'Alice'
                  , 'Nguyen'
                  , 'alice.nguyen@iu.edu'
                  , '812-555-1001'
                  , 'Indiana University'
                )
                , (
                  'Jacob'
                  , 'Reed'
                  , 'jacob.reed@iu.edu'
                  , '812-555-1002'
                  , 'Indiana University'
                )
                , (
                  'Lila'
                  , 'Mendoza'
                  , 'lila.mendoza@iu.edu'
                  , '812-555-1003'
                  , 'Indiana University'
                )
                , (
                  'Marcus'
                  , 'Taylor'
                  , 'marcus.taylor@iu.edu'
                  , '812-555-1004'
                  , 'Indiana University'
                )
                , (
                  'Priya'
                  , 'Patel'
                  , 'priya.patel@iu.edu'
                  , '812-555-1005'
                  , 'Indiana University'
                )
                , (
                  'David'
                  , 'Klein'
                  , 'david.klein@iu.edu'
                  , '812-555-1006'
                  , 'Indiana University'
                )
                , (
                  'Samantha'
                  , 'Park'
                  , 'samantha.park@iu.edu'
                  , '812-555-1007'
                  , 'Indiana University'
                )
                , (
                  'Ethan'
                  , 'Brooks'
                  , 'ethan.brooks@iu.edu'
                  , '812-555-1008'
                  , 'Indiana University'
                )
                , (
                  'Maya'
                  , 'Johnson'
                  , 'maya.johnson@iu.edu'
                  , '812-555-1009'
                  , 'Indiana University'
                )
                , (
                  'Noah'
                  , 'Sanchez'
                  , 'noah.sanchez@iu.edu'
                  , '812-555-1010'
                  , 'Indiana University'
                );


              INSERT INTO
                tutor (first_name, last_name, email, phone, experience)
              VALUES
                (
                  'Sofia'
                  , 'Morales'
                  , 'sofia.m@tutors.com'
                  , '317-555-2101'
                  , '3 years tutoring Calculus I and II'
                )
                , (
                  'Ethan'
                  , 'Wright'
                  , 'ethan.w@tutors.com'
                  , '317-555-2102'
                  , 'CS graduate, focuses on Java and SQL'
                )
                , (
                  'Isabella'
                  , 'Hunt'
                  , 'isabella.h@tutors.com'
                  , '317-555-2103'
                  , 'Experienced in academic writing and grammar'
                )
                , (
                  'Logan'
                  , 'Kim'
                  , 'logan.k@tutors.com'
                  , '317-555-2104'
                  , '2 years of Python programming tutoring'
                )
                , (
                  'Zara'
                  , 'Lopez'
                  , 'zara.l@tutors.com'
                  , '317-555-2105'
                  , 'Specializes in data visualization and Excel'
                )
                , (
                  'Oliver'
                  , 'Chase'
                  , 'oliver.c@tutors.com'
                  , '317-555-2106'
                  , 'Business analytics tutor with MBA background'
                )
                , (
                  'Hannah'
                  , 'Steele'
                  , 'hannah.s@tutors.com'
                  , '317-555-2107'
                  , 'Tutors statistics and R programming'
                )
                , (
                  'Carlos'
                  , 'Baker'
                  , 'carlos.b@tutors.com'
                  , '317-555-2108'
                  , 'Fluent in Spanish and English, teaches SQL'
                )
                , (
                  'Nina'
                  , 'Tran'
                  , 'nina.t@tutors.com'
                  , '317-555-2109'
                  , 'Specializes in web development and HTML/CSS'
                )
                , (
                  'Dylan'
                  , 'Cooper'
                  , 'dylan.c@tutors.com'
                  , '317-555-2110'
                  , '5 years of experience tutoring database design'
                );


              INSERT INTO
                tutor_req (tutor_id, availability, format, rate_free)
              VALUES
                (1, 'Weekdays 2–6 PM', 'In-person', 0.00)
                , (2, 'Weekends only', 'Online', 15.00)
                , (3, 'Mon/Wed mornings', 'Online', 20.00)
                , (4, 'Flexible hours', 'In-person', 10.00)
                , (5, 'Evenings 5–9 PM', 'Online', 18.00)
                , (6, 'Tues/Thurs afternoons', 'Hybrid', 25.00)
                , (7, 'Weekends 10–4 PM', 'Online', 12.00)
                , (8, 'Weekdays after 3 PM', 'In-person', 0.00)
                , (9, 'Mornings only', 'Online', 15.00)
                , (10, 'Flexible schedule', 'Hybrid', 20.00);


              INSERT INTO
                course (course_number, course_name, instructor, description)
              VALUES
                (
                  'INFO-I101'
                  , 'Introduction to Informatics'
                  , 'Dr. Solie'
                  , 'Overview of informatics and computing systems'
                )
                , (
                  'CSCI-C200'
                  , 'Intro to Programming'
                  , 'Dr. Chen'
                  , 'Problem-solving through Python coding'
                )
                , (
                  'INFO-I308'
                  , 'Information Representation'
                  , 'Dr. Green'
                  , 'Covers data modeling and ER diagrams'
                )
                , (
                  'INFO-I202'
                  , 'Social Informatics'
                  , 'Dr. Lin'
                  , 'Explores the social impacts of computing'
                )
                , (
                  'CSCI-C343'
                  , 'Data Structures'
                  , 'Dr. Brown'
                  , 'Study of algorithms and data organization'
                )
                , (
                  'INFO-I303'
                  , 'Organizational Informatics'
                  , 'Dr. Patel'
                  , 'Examines information flow in organizations'
                )
                , (
                  'INFO-I210'
                  , 'Information Infrastructure I'
                  , 'Dr. Fox'
                  , 'Focus on relational databases and SQL'
                )
                , (
                  'INFO-I300'
                  , 'Human–Computer Interaction'
                  , 'Dr. Ramirez'
                  , 'Principles of user-centered design'
                )
                , (
                  'CSCI-A201'
                  , 'Intro to Java Programming'
                  , 'Dr. Gupta'
                  , 'Basic Java and object-oriented programming'
                )
                , (
                  'INFO-I330'
                  , 'Database Systems'
                  , 'Dr. Wells'
                  , 'Covers relational theory and practical SQL'
                );


              INSERT INTO
                request (
                  budget
                  , schedule
                  , topics
                  , format
                  , student_id
                  , course_id
                )
              VALUES
                (
                  25.00
                  , 'Mon/Wed afternoons'
                  , 'SQL basics'
                  , 'Online'
                  , 1
                  , 7
                )
                , (
                  0.00
                  , 'Tues/Thurs evenings'
                  , 'Intro to Java'
                  , 'In-person'
                  , 2
                  , 9
                )
                , (
                  30.00
                  , 'Weekends 10–2'
                  , 'Database normalization'
                  , 'Online'
                  , 3
                  , 10
                )
                , (
                  15.00
                  , 'Mon/Thu 3–5 PM'
                  , 'Python debugging'
                  , 'Hybrid'
                  , 4
                  , 2
                )
                , (
                  0.00
                  , 'Flexible'
                  , 'Calculus I review'
                  , 'In-person'
                  , 5
                  , 5
                )
                , (
                  20.00
                  , 'Wed/Fri evenings'
                  , 'User interface critique'
                  , 'Online'
                  , 6
                  , 8
                )
                , (
                  18.00
                  , 'Weekends only'
                  , 'Excel pivot tables'
                  , 'Hybrid'
                  , 7
                  , 6
                )
                , (
                  22.00
                  , 'Weekdays 5–8 PM'
                  , 'Writing academic papers'
                  , 'Online'
                  , 8
                  , 3
                )
                , (
                  0.00
                  , 'Mornings only'
                  , 'Data visualization with Tableau'
                  , 'Online'
                  , 9
                  , 4
                )
                , (
                  25.00
                  , 'Fri 2–6 PM'
                  , 'Python loops and conditionals'
                  , 'In-person'
                  , 10
                  , 2
                );


              INSERT INTO
                overseer (first_name, last_name, email, role)
              VALUES
                ('Janel', 'Solie', 'jsolie@iu.edu', 'Mentor')
                , ('Patrick', 'Green', 'pgreen@iu.edu', 'Coordinator')
                , ('Laura', 'Ng', 'lng@iu.edu', 'Supervisor')
                , ('James', 'Harris', 'jharris@iu.edu', 'Mentor')
                , ('Monica', 'Lee', 'mlee@iu.edu', 'Coordinator')
                , ('Kevin', 'Brown', 'kbrown@iu.edu', 'Manager')
                , ('Diana', 'Owens', 'dowens@iu.edu', 'Supervisor')
                , ('Ryan', 'Miller', 'rmiller@iu.edu', 'Mentor')
                , ('Sophie', 'Clark', 'sclark@iu.edu', 'Coordinator')
                , ('Thomas', 'Wong', 'twong@iu.edu', 'Supervisor');


              INSERT INTO
                tutor_match (
                  request_id
                  , tutor_id
                  , match_status
                  , payment_status
                  , notes
                  , overseer_id
                )
              VALUES
                (
                  1
                  , 2
                  , 'Active'
                  , 'Paid'
                  , 'Student prefers evening lessons'
                  , 1
                )
                , (
                  2
                  , 4
                  , 'Pending'
                  , 'Unpaid'
                  , 'Tutor awaiting confirmation'
                  , 2
                )
                , (
                  3
                  , 10
                  , 'Completed'
                  , 'Paid'
                  , 'Successful SQL session'
                  , 3
                )
                , (
                  4
                  , 6
                  , 'Active'
                  , 'Pending'
                  , 'Session scheduled for Monday'
                  , 4
                )
                , (5, 1, 'Active', 'Free', 'Volunteer session', 5)
                , (
                  6
                  , 8
                  , 'Completed'
                  , 'Paid'
                  , 'Good feedback from student'
                  , 6
                )
                , (
                  7
                  , 7
                  , 'Active'
                  , 'Paid'
                  , 'Hybrid format working well'
                  , 7
                )
                , (
                  8
                  , 3
                  , 'Pending'
                  , 'Unpaid'
                  , 'Request created recently'
                  , 8
                )
                , (
                  9
                  , 5
                  , 'Completed'
                  , 'Paid'
                  , 'Visual design project review'
                  , 9
                )
                , (
                  10
                  , 9
                  , 'Active'
                  , 'Pending'
                  , 'Tutor traveling next week'
                  , 10
                );