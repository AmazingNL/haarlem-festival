-- Allow order_ticket rows without a ticket_type so every purchase type gets a QR ticket.
USE haarlem_festival;

ALTER TABLE order_ticket DROP FOREIGN KEY fk_order_ticket_type;
ALTER TABLE order_ticket MODIFY COLUMN ticket_type_id BIGINT UNSIGNED NULL;
ALTER TABLE order_ticket ADD CONSTRAINT fk_order_ticket_type
    FOREIGN KEY (ticket_type_id) REFERENCES ticket_type(ticket_type_id);
