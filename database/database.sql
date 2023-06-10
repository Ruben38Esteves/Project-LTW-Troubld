DROP TABLE IF EXISTS message_inf;
DROP TABLE IF EXISTS chat;
DROP TABLE IF EXISTS hastag_on_ticket;
DROP TABLE IF EXISTS faq_on_ticket;
DROP TABLE IF EXISTS faq;
DROP TABLE IF EXISTS hashtag;
DROP TABLE IF EXISTS agentAssignedTicket;
DROP TABLE IF EXISTS agentWorksDepartment;
DROP TABLE IF EXISTS clientOwnsTicket;
DROP TABLE IF EXISTS update_ticket;
DROP TABLE IF EXISTS ticket;
DROP TABLE IF EXISTS department;
DROP TABLE IF EXISTS administrator;
DROP TABLE IF EXISTS agent;
DROP TABLE IF EXISTS client;
DROP TABLE IF EXISTS users;
DROP TRIGGER IF EXISTS check_agent_department;
DROP TRIGGER IF EXISTS create_chat_trigger;


CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name_ TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    birthday DATETIME NOT NULL,
    email TEXT NOT NULL CONSTRAINT user_email_uk UNIQUE

);

CREATE TABLE client(
    id INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE,
    PRIMARY KEY (id)
);

CREATE TABLE agent(
    id INTEGER NOT NULL REFERENCES client (id) ON UPDATE CASCADE,
    PRIMARY KEY (id)
);


CREATE TABLE administrator(
    id INTEGER NOT NULL REFERENCES agent (id) ON UPDATE CASCADE,
    PRIMARY KEY (id)
);


CREATE TABLE department(
    
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name_ TEXT NOT NULL,
    description_ TEXT NOT NULL
);

CREATE TABLE ticket (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    subtitle_ NOT NULL DEFAULT "ehehhe",
    description_ TEXT NOT NULL,
    status_ TEXT CHECK( status_ IN ('Recived' ,'Open', 'Assigned', 'Closed') ) NOT NULL DEFAULT 'Recived',
    priority_ INTEGER NOT NULL DEFAULT 0,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    department_id INTEGER REFERENCES department (id) ON UPDATE CASCADE DEFAULT 0,
    id_client INTEGER NOT NULL REFERENCES client (id) ON UPDATE CASCADE
);

CREATE TABLE update_ticket(
    
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    content TEXT,
    update_time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    id_agent INTEGER NOT NULL REFERENCES agent (id) ON UPDATE CASCADE,
    id_ticket INTEGER NOT NULL REFERENCES ticket (id) ON UPDATE CASCADE
);


CREATE TABLE agentWorksDepartment(
    
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_agent INTEGER NOT NULL REFERENCES agent (id) ON UPDATE CASCADE,
    id_department INTEGER NOT NULL REFERENCES department (id) ON UPDATE CASCADE

);

--now it checks if agents works on department
CREATE TABLE agentAssignedTicket(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_agent INTEGER NOT NULL REFERENCES agent (id) ON UPDATE CASCADE,
    id_ticket INTEGER NOT NULL REFERENCES ticket (id) ON UPDATE CASCADE
);

CREATE TABLE hashtag (
    
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name_ TEXT NOT NULL
);

CREATE TABLE faq (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    question TEXT NOT NULL,
    answer TEXT NOT NULL
);

CREATE TABLE faq_on_ticket(
    id_faq INTEGER NOT NULL REFERENCES faq (id) ON UPDATE CASCADE,
    id_ticket INTEGER NOT NULL REFERENCES ticket (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_faq, id_ticket)
);


CREATE TABLE hastag_on_ticket(
    id_hashtag INTEGER NOT NULL REFERENCES hashtag (id) ON UPDATE CASCADE,
    id_ticket INTEGER NOT NULL REFERENCES ticket (id) ON UPDATE CASCADE,
    PRIMARY KEY (id_hashtag, id_ticket)
);

CREATE TABLE chat (
    
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_ticket INTEGER NOT NULL REFERENCES ticket (id) ON UPDATE CASCADE
);

CREATE TABLE message_inf (
    
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    content TEXT NOT NULL,
    time_sent DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    id_client INTEGER NOT NULL REFERENCES client (id) ON UPDATE CASCADE,
    id_chat INTEGER NOT NULL REFERENCES chat (id) ON UPDATE CASCADE
);


--Triggers

CREATE TRIGGER check_agent_assigned_ticket
BEFORE INSERT ON agentAssignedTicket
FOR EACH ROW
WHEN NOT EXISTS (
    SELECT *
    FROM agentWorksDepartment AS awd
    INNER JOIN ticket AS t ON awd.id_agent = NEW.id_agent AND t.id = NEW.id_ticket
    WHERE awd.id_department = t.department_id
)
BEGIN
    SELECT RAISE(ABORT, 'Agent is not assigned to the ticket department');
END;


CREATE TRIGGER create_chat_trigger AFTER INSERT ON ticket
BEGIN
    INSERT INTO chat (id_ticket) VALUES (NEW.id);
END;
-- POPULATE --


-- Populate the "users" table
INSERT INTO users (id, name_, username, password_hash, birthday, email) VALUES
    (1, 'John Doe', 'john123', 'hash1', '1990-05-01', 'john.doe@example.com'),
    (2, 'Jane Smith', 'jane123', 'hash2', '1985-08-15', 'jane.smith@example.com'),
    (3, 'Michael Johnson', 'michael123', 'hash3', '1992-11-27', 'michael.johnson@example.com'),
    (4, 'Emily Davis', 'emilydavis','hash4', '1995-04-03', 'emily.davis@example.com'),
    (5, 'Robert Wilson', 'robertwilllzon','hash5', '1988-09-10', 'robert.wilson@example.com'),
    (6, 'Sarah Thompson', 'sarahhh12','hash6', '1993-06-19', 'sarah.thompson@example.com'),
    (7, 'David Anderson', 'david07','hash7', '1998-02-22', 'david.anderson@example.com'),
    (8, 'Ruben Esteves', 'rubene','ruben', '1998-02-22', 'ruben38@gmail.com');

-- Populate the "client" table
INSERT INTO client (id) VALUES (1), (2), (3), (4), (5), (6), (7), (8);

-- Populate the "agent" table
INSERT INTO agent (id) VALUES (1), (2), (3), (4), (5), (6), (7), (8);

-- Populate the "administrator" table
INSERT INTO administrator (id) VALUES (1), (8);

-- Populate the "department" table
INSERT INTO department (id, name_, description_) VALUES
    (1, 'Sales', 'Responsible for sales and revenue generation.'),
    (2, 'Marketing', 'Responsible for promoting and advertising products or services.');


-- Populate the "agentWorksDepartment" table
INSERT INTO agentWorksDepartment (id_agent, id_department) VALUES
    (1, 1),
    (2, 1),
    (8, 2),
    (3, 2);


-- Populate the "ticket" table
INSERT INTO ticket (id, description_, status_, priority_, department_id, id_client) VALUES
    (1, 'Issue with product delivery', 'Open', 2, 1, 1),
    (2, 'Login problem', 'Assigned', 1, 2, 2),
    (3, 'Billing discrepancy', 'Open', 1, 1, 3),
    (4, 'Website error', 'Recived', 0, 2, 4),
    (5, 'Product inquiry', 'Closed', 0, 1, 5),
    (6, 'Issue with product delivery', 'Closed', 2, 1, 1),
    (7, 'Product inquiry', 'Closed', 0, 1, 5);



    

-- Populate the "agentAssignedTicket" table
INSERT INTO agentAssignedTicket (id_agent, id_ticket) VALUES
    (1, 1);



    
-- Populate the "update_ticket" table
INSERT INTO update_ticket (content, id_agent, id_ticket) VALUES
    ('Issue identified and under investigation.', 1, 1),
    ('Resolution in progress.', 2, 1),
    ('Ticket closed. Issue resolved.', 3, 1),
    ('Investigating login problem.', 1, 2),
    ('Issue resolved. User can log in successfully now.', 2, 2);
-- Populate the "hashtag" table
INSERT INTO hashtag (name_) VALUES
    ('urgent'),
    ('technical'),
    ('payment');

-- Populate the "faq" table
INSERT INTO faq (question, answer) VALUES
    ('How do I track my order?', 'You can track your order by logging into your account and visiting the order tracking page.'),
    ('What payment methods do you accept?', 'We accept all major credit cards, PayPal, and bank transfers.'),
    ('How long does shipping usually take?', 'Shipping usually takes 3-5 business days.');

-- Populate the "faq_on_ticket" table
INSERT INTO faq_on_ticket (id_faq, id_ticket) VALUES
    (1, 1),
    (2, 1),
    (3, 2);

-- Populate the "hastag_on_ticket" table
INSERT INTO hastag_on_ticket (id_hashtag, id_ticket) VALUES
    (1, 1),
    (2, 1),
    (3, 2);

-- Populate the "chat" table
INSERT INTO chat (id_ticket) VALUES
    (1),
    (2),
    (3);

-- Populate the "message_inf" table
INSERT INTO message_inf (content, id_client, id_chat) VALUES
    ('Hello, I have an issue with my recent order.', 1, 1),
    ('Sure, I will assist you with that.', 2, 1),
    ('Thank you for your help!', 1, 1),
    ('I forgot my password. Can you help me reset it?', 2, 2),
    ('Yes, I can assist you with password reset.', 1, 2);
