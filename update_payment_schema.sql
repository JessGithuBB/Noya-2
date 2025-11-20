-- Script SQL pour ajouter les fonctionnalités de paiement
-- À exécuter manuellement dans phpMyAdmin ou votre client SQL

-- Modifier la table order pour ajouter les colonnes de paiement
ALTER TABLE `order` 
ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) NOT NULL DEFAULT 'visa',
ADD COLUMN IF NOT EXISTS payment_status VARCHAR(50) NOT NULL DEFAULT 'pending',
ADD COLUMN IF NOT EXISTS payment_transaction_id LONGTEXT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS paid_at DATETIME DEFAULT NULL;

-- Supprimer l'ancienne colonne stripe_payment_intent_id si elle existe
ALTER TABLE `order` DROP COLUMN IF EXISTS stripe_payment_intent_id;

-- Modifier les colonnes existantes si nécessaire
ALTER TABLE `order` 
MODIFY COLUMN created_at DATETIME NOT NULL,
MODIFY COLUMN shipping_address LONGTEXT DEFAULT NULL,
MODIFY COLUMN billing_address LONGTEXT DEFAULT NULL;

-- Modifier la table order_item si nécessaire
-- Vérifier si la colonne order_ref_id existe et la renommer en order_id
-- ALTER TABLE order_item CHANGE order_ref_id order_id INT NOT NULL;

-- Recréer les index et contraintes si nécessaire
-- ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id);

-- Fin du script




