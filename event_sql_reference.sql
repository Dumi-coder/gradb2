-- SQL Reference for Event Feature Changes
-- Place this file outside all folders for easy access

-- 1. Add a new column to the events table (e.g., sponsor)
ALTER TABLE events ADD COLUMN sponsor VARCHAR(255) AFTER organizer;

-- 2. Remove a column from the events table
ALTER TABLE events DROP COLUMN sponsor;

-- 3. Change the data type of a column (e.g., sponsor to TEXT)
ALTER TABLE events MODIFY COLUMN sponsor TEXT;

-- 4. Rename a column (e.g., sponsor to event_sponsor)
ALTER TABLE events CHANGE sponsor event_sponsor VARCHAR(255);

-- 5. Create a new events table (if needed)
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    organizer VARCHAR(255),
    sponsor VARCHAR(255),
    event_type VARCHAR(100),
    description TEXT,
    date DATE,
    time VARCHAR(50),
    location VARCHAR(255),
    expected_attendees INT,
    status VARCHAR(50),
    submitted_date DATE,
    budget_requested DECIMAL(10,2)
);

-- 6. Drop the events table
DROP TABLE events;

-- 7. Update a value in the sponsor column for a specific event
UPDATE events SET sponsor = 'New Sponsor Name' WHERE id = 1;

-- 8. Select all events with sponsor info
SELECT * FROM events WHERE sponsor IS NOT NULL;

-- 9. Delete all events with a specific sponsor
DELETE FROM events WHERE sponsor = 'Old Sponsor Name';

-- 10. List all columns in the events table
SHOW COLUMNS FROM events;

-- End of SQL Reference
