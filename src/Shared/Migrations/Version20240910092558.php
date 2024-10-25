<?php

declare(strict_types=1);

namespace Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240910092558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE questionnaire (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))'
        );

        $this->addSql(
            'CREATE TABLE user_questionnaire (id UUID NOT NULL, questionnaire_id UUID NOT NULL, user_id UUID NOT NULL, recommended_products JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))'
        );

        $this->addSql(
            'CREATE TABLE question (id UUID NOT NULL, priority integer NOT NULL, questionnaire_id UUID NOT NULL, parent_id UUID DEFAULT NULL, value TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0), PRIMARY KEY(id))'
        );

        $this->addSql(
            'CREATE TABLE answer (id UUID NOT NULL, value TEXT NOT NULL, question_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))'
        );

        $this->addSql(
            'CREATE TABLE action (id UUID NOT NULL, type VARCHAR(36) NOT NULL, products JSON DEFAULT NULL, answer_id UUID NOT NULL, question_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))'
        );

        // add questionnaire
        $this->addSql(
            <<<'EOD'
            insert into questionnaire (id, created_at, updated_at)
            values  ('94cf1c49-b747-4a1b-a03c-c63f4110a54a', '2024-09-10 12:41:06', '2024-09-10 12:41:06');
EOD
        );

        // add questions
        $this->addSql(
            <<<'EOD'
        insert into question (id, questionnaire_id, parent_id, value, created_at, updated_at, priority) values 
        ('49b9b281-6b44-4a78-862e-ba74fcb75d0d', '94cf1c49-b747-4a1b-a03c-c63f4110a54a', '30d65970-3510-41d7-b2d9-2a25d014ea30', 'Was the product A or Product B product you tried before effective?', '2024-09-10 12:41:49', '2024-09-10 12:41:49', 1),
        ('067ad43b-4a15-4a8d-b120-c20e8dd5e6c6', '94cf1c49-b747-4a1b-a03c-c63f4110a54a', null, 'Do you find product A useful?', '2024-09-10 12:41:49', '2024-09-10 12:41:49', 1),
        ('82f60b76-b895-4beb-9c65-863344e45204', '94cf1c49-b747-4a1b-a03c-c63f4110a54a', '30d65970-3510-41d7-b2d9-2a25d014ea30', 'Was the Product A or Product B product you tried before effective?', '2024-09-10 12:41:49', '2024-09-10 12:41:49', 2),
        ('30d65970-3510-41d7-b2d9-2a25d014ea30', '94cf1c49-b747-4a1b-a03c-c63f4110a54a', null, 'Have you tried any of the following treatments before?', '2024-09-10 12:41:49', '2024-09-10 12:41:49', 2),
        ('93862a57-71af-41e0-b047-303cb8f0e20e', '94cf1c49-b747-4a1b-a03c-c63f4110a54a', '30d65970-3510-41d7-b2d9-2a25d014ea30', 'Which is your preferred treatment?', '2024-09-10 12:41:49', '2024-09-10 12:41:49', 3),
        ('b96aa45a-b2a4-4bae-824b-131d5a140d1f', '94cf1c49-b747-4a1b-a03c-c63f4110a54a', null, 'Do you have, or have you ever had, any heart or neurological conditions?', '2024-09-10 12:41:49', '2024-09-10 12:41:49', 3),
        ('21674cbb-7ffc-46fd-9c36-7507ac6efd2a', '94cf1c49-b747-4a1b-a03c-c63f4110a54a', null, 'Do any of the listed medical conditions apply to you?', '2024-09-10 12:41:49', '2024-09-10 12:41:49', 4),
        ('71905c0e-fc23-4c05-b4cb-39df8b5b78bd', '94cf1c49-b747-4a1b-a03c-c63f4110a54a', null, 'Are you taking any of the following products?', '2024-09-10 12:41:49', '2024-09-10 12:41:49', 5);
EOD
        );

        // add answers
        $this->addSql(
            <<<'EOD'
insert into answer (id, value, question_id, created_at, updated_at)
values  ('87e66701-1d47-4107-8e4c-1010e3a50b3c', 'Yes', '067ad43b-4a15-4a8d-b120-c20e8dd5e6c6', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('a28644c0-ad09-494f-97be-12f506320187', 'No', '067ad43b-4a15-4a8d-b120-c20e8dd5e6c6', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('3eb81ee1-7925-4db0-affc-9a7b805ea91a', 'Product A or Product B', '30d65970-3510-41d7-b2d9-2a25d014ea30', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('bcb12603-8bb3-4d78-9278-45d2acbd1e81', 'Product C or Product D', '30d65970-3510-41d7-b2d9-2a25d014ea30', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('cf220e01-ddba-427a-9609-886f601ce4fe', 'Both', '30d65970-3510-41d7-b2d9-2a25d014ea30', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('aef6133a-5bab-4d38-8946-99cfce762cbc', 'None of the above', '30d65970-3510-41d7-b2d9-2a25d014ea30', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('d916d728-08cf-4f76-ac24-6826baecbde1', 'Yes', '49b9b281-6b44-4a78-862e-ba74fcb75d0d', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('9ffcc603-7517-431b-b222-acb7477ad079', 'No', '49b9b281-6b44-4a78-862e-ba74fcb75d0d', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('86483748-a263-4b8a-8df7-c110170a604b', 'Yes', '82f60b76-b895-4beb-9c65-863344e45204', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('8059ee09-bf81-456f-b0da-8c2411922f4c', 'No', '82f60b76-b895-4beb-9c65-863344e45204', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('c8f37190-cc20-4faf-9fb5-e33ef4082f46', 'Product A or Product B', '93862a57-71af-41e0-b047-303cb8f0e20e', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('2a45583a-e705-450a-aada-7b49c642fb4e', 'Product C or Product D', '93862a57-71af-41e0-b047-303cb8f0e20e', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('a080ae2a-6f75-4787-9097-528cb7e305a6', 'None of the above', '93862a57-71af-41e0-b047-303cb8f0e20e', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('d613c4e1-cccd-4744-b22b-e2048e6ce94d', 'Yes', 'b96aa45a-b2a4-4bae-824b-131d5a140d1f', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('16625fda-019f-4d91-8808-4972fb6561e5', 'No', 'b96aa45a-b2a4-4bae-824b-131d5a140d1f', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('132959f7-447d-4fe6-8872-38bb5bfc9a2e', 'Significant liver problems', '21674cbb-7ffc-46fd-9c36-7507ac6efd2a', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('bbbb054b-f33e-45ac-a23a-4ada11c3ddef', 'Test answer', '21674cbb-7ffc-46fd-9c36-7507ac6efd2a', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('cf284dff-92f5-4da6-96cf-419dcac67421', 'Abnormal blood pressure (lower than 90/50 mmHg or higher than 160/90 mmHg)', '21674cbb-7ffc-46fd-9c36-7507ac6efd2a', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('aa7fdbe0-ce28-4ab6-a1a7-b41001d44bb8', 'Test answer 2', '21674cbb-7ffc-46fd-9c36-7507ac6efd2a', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('b7e07178-6ad3-4201-b349-a058966fa6a0', 'I don''t have any of these conditions', '21674cbb-7ffc-46fd-9c36-7507ac6efd2a', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('7584c4a7-fa5a-4000-890f-ffa395511b13', 'Test answer 3', '71905c0e-fc23-4c05-b4cb-39df8b5b78bd', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('d920e3f8-97df-45f0-af16-ea016f517d42', 'Test answer 4', '71905c0e-fc23-4c05-b4cb-39df8b5b78bd', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('9c3473bf-1999-41e7-bd32-6e452e7823ba', 'Test answer 5', '71905c0e-fc23-4c05-b4cb-39df8b5b78bd', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('9593bf3c-a6d8-4112-b576-7aee1f9e2f21', 'Test answer 6', '71905c0e-fc23-4c05-b4cb-39df8b5b78bd', '2024-09-10 13:07:57', '2024-09-10 13:07:57'),
        ('f10383c2-279a-44b0-8b07-fec77dc330e9', 'Test answer 7', '71905c0e-fc23-4c05-b4cb-39df8b5b78bd', '2024-09-10 13:07:57', '2024-09-10 13:07:57');
EOD
        );

        // add actions
        $this->addSql(
            <<<'EOD'
insert into action (id, type, products, answer_id, question_id, created_at, updated_at)
values  ('d16a99d5-0e64-46b2-bfca-250cc3aa2e3e', 'ASK_SUBQUESTION', null, '3eb81ee1-7925-4db0-affc-9a7b805ea91a', '49b9b281-6b44-4a78-862e-ba74fcb75d0d', '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('ed583fe0-7855-48ba-b50b-4117589ac6f8', 'ASK_SUBQUESTION', null, 'bcb12603-8bb3-4d78-9278-45d2acbd1e81', '82f60b76-b895-4beb-9c65-863344e45204', '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('60e33cdb-2598-4206-84b8-11a0a525d1cf', 'ASK_SUBQUESTION', null, 'cf220e01-ddba-427a-9609-886f601ce4fe', '93862a57-71af-41e0-b047-303cb8f0e20e', '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('462c51e5-97ff-47da-8203-31a0a46707bd', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', 'a28644c0-ad09-494f-97be-12f506320187', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('db4de7f9-fe1b-4a84-ae1e-399117bb2e96', 'INCLUDE_PRODUCTS', '["product-a-50","product-b-10"]', 'aef6133a-5bab-4d38-8946-99cfce762cbc', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('71cc0857-b26f-49c0-a085-532dd40d8c83', 'INCLUDE_PRODUCTS', '["product-a-50"]', 'd916d728-08cf-4f76-ac24-6826baecbde1', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('e3f30e8d-b44d-4d3d-8638-8a4b22032005', 'INCLUDE_PRODUCTS', '["product-b-20"]', '9ffcc603-7517-431b-b222-acb7477ad079', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('507b31b7-019e-430d-8ac9-db8ad37389ed', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100"]', '9ffcc603-7517-431b-b222-acb7477ad079', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('71cefb22-68b8-4a1a-8a02-7ab4a837d4d2', 'INCLUDE_PRODUCTS', '["product-b-10"]', '86483748-a263-4b8a-8df7-c110170a604b', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('193a5485-d34f-4528-be45-68107c85dce6', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100"]', '86483748-a263-4b8a-8df7-c110170a604b', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('2f6f1866-3d8b-458c-83d1-c35898a02623', 'EXCLUDE_PRODUCTS', '["product-b-10","product-b-20"]', 'd916d728-08cf-4f76-ac24-6826baecbde1', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('d0cce0f1-b583-4fa1-b196-8a09f38cb41c', 'INCLUDE_PRODUCTS', '["product-a-100"]', '8059ee09-bf81-456f-b0da-8c2411922f4c', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('ab8a580f-5cbb-4ec2-bdf9-1872324f068d', 'EXCLUDE_PRODUCTS', '["product-b-10","product-b-20"]', '8059ee09-bf81-456f-b0da-8c2411922f4c', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('aa3fe188-e82e-40e3-88e5-9105cf801389', 'INCLUDE_PRODUCTS', '["product-a-100"]', 'c8f37190-cc20-4faf-9fb5-e33ef4082f46', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('64321246-7db6-4a24-b814-f1f82bb88ba1', 'EXCLUDE_PRODUCTS', '["product-b-10","product-b-20"]', 'c8f37190-cc20-4faf-9fb5-e33ef4082f46', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('159e7b99-eb77-414f-b80c-dab2f5672151', 'INCLUDE_PRODUCTS', '["product-b-20"]', '2a45583a-e705-450a-aada-7b49c642fb4e', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('d45d2626-2a96-47b0-bc02-5a8f4894b714', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100"]', '2a45583a-e705-450a-aada-7b49c642fb4e', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('391ace88-fb33-494d-b5c6-1c45300ed228', 'INCLUDE_PRODUCTS', '["product-a-100","product-b-20"]', 'a080ae2a-6f75-4787-9097-528cb7e305a6', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('6acc0f12-e2fa-4ab3-bc90-cb473e6b9404', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', '16625fda-019f-4d91-8808-4972fb6561e5', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('f51d5ef2-e5bb-4d15-8a7f-a47aca619e53', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', '132959f7-447d-4fe6-8872-38bb5bfc9a2e', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('27151aaf-a068-47be-87a9-e79c79a2abec', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', 'bbbb054b-f33e-45ac-a23a-4ada11c3ddef', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('82defcdd-49bd-48e3-82e9-2e83ff02dcee', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', 'cf284dff-92f5-4da6-96cf-419dcac67421', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('1b70274b-8b45-4323-8e1a-fa624a59bab5', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', 'aa7fdbe0-ce28-4ab6-a1a7-b41001d44bb8', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('87e12916-c117-4132-9484-b9ca1f225a7e', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', '7584c4a7-fa5a-4000-890f-ffa395511b13', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('05318909-b58a-410a-af4d-83f28ca06253', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', 'd920e3f8-97df-45f0-af16-ea016f517d42', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('3158eddb-8d64-4bd3-8e3c-49900470b263', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', '9c3473bf-1999-41e7-bd32-6e452e7823ba', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40'),
        ('237d0397-5006-439c-a344-ccbb442c7fac', 'EXCLUDE_PRODUCTS', '["product-a-50","product-a-100","product-b-10","product-b-20"]', '9593bf3c-a6d8-4112-b576-7aee1f9e2f21', null, '2024-09-10 13:31:40', '2024-09-10 13:31:40');
EOD
        );
    }

    public function down(Schema $schema): void
    {
        // one way migration
    }
}
