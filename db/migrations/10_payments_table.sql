USE haarlem_festival;

CREATE TABLE IF NOT EXISTS payments (
  payment_id       INT AUTO_INCREMENT PRIMARY KEY,
  order_id         INT          NOT NULL,
  provider         VARCHAR(100) NOT NULL DEFAULT 'stripe',
  provider_payment_id VARCHAR(255) DEFAULT NULL,
  amount           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  currency         VARCHAR(10)  NOT NULL DEFAULT 'EUR',
  status           VARCHAR(50)  NOT NULL DEFAULT 'pending',
  created_at       DATETIME     DEFAULT CURRENT_TIMESTAMP,
  paid_at          DATETIME     DEFAULT NULL
);
