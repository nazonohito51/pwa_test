<?php

use App\DataAccess\Eloquent\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->storeUser($this->getUserParams());

        /**
         * id  name email password remember_token created_at updated_at nickname role api_token avator
         * 6    Symfony    test1@test.com    $2y$10$hwt4qkIlj1B7fSF3nP61jOiBfsRavAu4Rp6uAU3CaafxyUo1eDD0e    null    2017-11-04 08:19:11    2017-11-04 08:19:51    Symfony    interim    UhQzk28cIQgApae37MLPKcIOty8srTYTorZVYVROfW4ZKZ0VbOFgaZBJdtgR    Symfony.png
         * 7    FuelPHP    test2@test.com    $2y$10$SXniNeVHPfhJTD95dDlFq.sHqdoAL9z8FG5l/mVF43Ev6eWltM0xu    null    2017-11-04 08:34:18    2017-11-04 08:34:38    FuelPHP    interim    jkMdJxMA3oHL6mJpj4FDk8gAN7pOfGZII53MCVGPZdViEN1TObCRW6cMyPmP    FuelPHP.png
         * 8    Slim    test3@test.com    $2y$10$4Be7DV5qHB0mMpK4Aifj0OtsHQ20xmP/xmhnQiKJi2m7wbXqYDc0q    null    2017-11-04 10:29:29    2017-11-04 10:29:55    Slim    interim    14xVBWB3xoa3qCeWbfeVl23rcLaUcdBNFFHdPzW0snAWemlxkUq2kBPMUSun    Slim.png
         * 9    CodeIgniter    test4@test.com    $2y$10$4fTwA70uy21t8/mcQCSrSOSKbBoVX1hCYgg6wY/ZHcVgmYF32VEz6    null    2017-11-05 05:22:49    2017-11-05 05:23:39    CodeIgniter    interim    Xh3HrsgRXLtnQpPsFLST28XuREc5Qy7LFEJVtcMdZqNmh7nRFl0iWexxvZ08    CodeIgniter.png
         * 10    Laravel    test5@test.com    $2y$10$z2JWzZsJQfVO.Um.OkAmqufDUgE07TrgWKzfok/eiZcagFpfmkAvi    null    2017-11-05 05:27:31    2017-11-05 05:28:15    Laravel    interim    qJvdmQBmtfRYsyQ2rcwLGFpDZ9F8P73Nlj7Ezrn8pjQOyXHVGunS54TlX8O1    Laravel.png
         * 11    Phalcon    test6@test.com    $2y$10$qJzKY4JNRydM0wPFha01w.P4c6vjaZU48.VmNE9IiI.iFNKhdLC/6    null    2017-11-05 05:29:53    2017-11-05 05:30:30    Phalcon    interim    5UBhKLKlTAP37qih4wmHUb0qDj5qwsowZvpOJRY34cuNv33f7hhLTD040ptg    Phalcon.png
         * 12    CakePHP    test7@test.com    $2y$10$T8fyBj28jFNibrlq4matbuKio55wp0hvGVOaVfDGPRvOcAZvZxXqi    null    2017-11-05 05:34:13    2017-11-05 05:34:42    CakePHP    interim    ONYbO4cKsrmkQnKK9AXpz07BkTg80euTRxmXacTdsLnwvwYMyg9614aXBI7V    CakePHP.png
         * 13    ZendFramework    test8@test.com    $2y$10$ofDB0EnA.Qn4/2erW6gZSe7XqtYBfu9nDPs413jrkT1Jx1nrUlM9G    null    2017-11-05 05:35:44    2017-11-05 05:47:07    ZendFramework    interim    lP6Vb5Qi4Fw2KLXCwL3eVPXzyzmcN9mYl5SKPb5E0AAknhOY8E7DnT0Fi0cR    ZendFramework.png
         * 14    barie    barie@test.com    $2y$10$ofDB0EnA.Qn4/2erW6gZSe7XqtYBfu9nDPs413jrkT1Jx1nrUlM9F    null    2017-11-05 05:35:44    2017-11-05 05:47:07    barie    interim    lP6Vb5Qi4Fw2KLXCwL3eVPXzyzmcN9mYl5SKPb5E0AAknhOY8E7DnT0Fi0cG    barie.png
         */
    }

    private function storeUser(array $user_params)
    {
        foreach ($user_params as $user_param) {
            $user = User::create($user_param['user']);
            $user->user_setting()->create([
                'notification' => false
            ]);
            $user->push_notifications()->create($user_param['push_notification']);
        }
    }

    private function getUserParams()
    {
        return [
            [
                'user' => [
                    'name' => 'Symfony',
                    'email' => 'test1@test.com',
                    'password' => '$2y$10$hwt4qkIlj1B7fSF3nP61jOiBfsRavAu4Rp6uAU3CaafxyUo1eDD0e',
                    'nickname' => 'Symfony',
                    'role' => 'interim',
                    'api_token' => 'UhQzk28cIQgApae37MLPKcIOty8srTYTorZVYVROfW4ZKZ0VbOFgaZBJdtgR',
                    'avator' => 'Symfony.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/etvQVI1Vqeo:APA91bFmPjNmIKVb5j3VS7zGy01xAlekNkCxAZbtlJ0JjK8yOMPxXp0TfsGb0jLXjOLiZxtn0t45_PqF-s7eAjkOEmDMo10in4lTH1d74YggwihpZfZ82tewf-VZIe_tYngTiJeX6cKo',
                    'key' => 'BO9scI7mOxOEzdcpJnCpfOL0LAI5PDppn+SJQsEKroYbU/Ame6889kJ4f8YBOW/BgG2TLfgcfLYWimYaoMws/bU=',
                    'token' => '41BHNQ1rBYRXynUP9xFH2w==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
            [
                'user' => [
                    'name' => 'FuelPHP',
                    'email' => 'test2@test.com',
                    'password' => '$2y$10$SXniNeVHPfhJTD95dDlFq.sHqdoAL9z8FG5l/mVF43Ev6eWltM0xu',
                    'nickname' => 'FuelPHP',
                    'role' => 'interim',
                    'api_token' => 'jkMdJxMA3oHL6mJpj4FDk8gAN7pOfGZII53MCVGPZdViEN1TObCRW6cMyPmP',
                    'avator' => 'FuelPHP.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/cJtN3pWLh_U:APA91bFfLkc1x7x3FpM4uLELpGamNiETUCHEP2oTNO8yoMiodDrAlxH0DKThCbsqVIkwzaKLN_BpvyKtE0NBb1ywHYzz9G7TnKjf2jHLxslNVCRpQ2IfXUqt7_13wJGooQdj-C7_-4jS',
                    'key' => 'BJQl0G3ILgUYRXGZur57yR63CBDj0F6U2n2V/CMhCcKuOg1aqA9STVmkbD7xbkLht18DUEia9Ez7sDDQDULnYTg=',
                    'token' => 'fXid9is6Wux8s8Y86zuEaw==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
            [
                'user' => [
                    'name' => 'Slim',
                    'email' => 'test3@test.com',
                    'password' => '$2y$10$4Be7DV5qHB0mMpK4Aifj0OtsHQ20xmP/xmhnQiKJi2m7wbXqYDc0q',
                    'nickname' => 'Slim',
                    'role' => 'interim',
                    'api_token' => '14xVBWB3xoa3qCeWbfeVl23rcLaUcdBNFFHdPzW0snAWemlxkUq2kBPMUSun',
                    'avator' => 'Slim.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/dm3C-_FCqCM:APA91bGdx_QmAxzDmAp2Tk4FpdBfYNqh9OlWLXgkG8Y61VTxsYSLCnCYKuiT-M97hMqwLnmaEwEZL39BKd1hQwc7mZxSRtWii4qcEzhyEy9oRnQ0f4IPYKp6v15B9c-MEm30_qFFTKYc',
                    'key' => 'BPRmeRP9IWURwcFX5igfSlzXVmJxj8BcnUsdP9hfMhM2+7eq+sJ+IL++m9DFkUsO5qKwJRDuE28bw0mgW7KsGJU=',
                    'token' => 'e6I/fzqVKTBBxAD75aQJ5g==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
            [
                'user' => [
                    'name' => 'CodeIgniter',
                    'email' => 'test4@test.com',
                    'password' => '$2y$10$4fTwA70uy21t8/mcQCSrSOSKbBoVX1hCYgg6wY/ZHcVgmYF32VEz6',
                    'nickname' => 'CodeIgniter',
                    'role' => 'interim',
                    'api_token' => 'Xh3HrsgRXLtnQpPsFLST28XuREc5Qy7LFEJVtcMdZqNmh7nRFl0iWexxvZ08',
                    'avator' => 'CodeIgniter.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/erebaNopBzY:APA91bHPjeL86QSlpm-mDJcAR7VNUZyEWj_n96rm7ob1i01zy0LDu8UnAuTY7Duu9shFG_jela2Gp_xaniJ4EsdAvL1hOUMyJ0qIevBM7aTyA7_QoWEy5y089TM6kgG-4ciWbBfC6wKd',
                    'key' => 'BPmxj7aGnMLCbYFfINvMbt6zK26PsCnjsmOReyHc7YEi/uukRBKH0rbEx+uR4YaaEvOvYPZD4FM0ale3Gv+FZcQ=',
                    'token' => 'AILxOAE4ZvJXLgyAXktVRg==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
            [
                'user' => [
                    'name' => 'Laravel',
                    'email' => 'test5@test.com',
                    'password' => '$2y$10$z2JWzZsJQfVO.Um.OkAmqufDUgE07TrgWKzfok/eiZcagFpfmkAvi',
                    'nickname' => 'Laravel',
                    'role' => 'interim',
                    'api_token' => 'qJvdmQBmtfRYsyQ2rcwLGFpDZ9F8P73Nlj7Ezrn8pjQOyXHVGunS54TlX8O1',
                    'avator' => 'Laravel.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/f0viFajQ2OQ:APA91bGj9wP6ySatghd65LgU3Cl-q0XPcTo6mB-ChU9ABt2biqNFrGomgrciHi0pghFWxV4MS0nSsPgxETM_A4mC9BgpSb7E5n9uR6STPE9tzjuFwQ43hDiZlfGSnD_PfBWhdvyHALhO',
                    'key' => 'BHTx+BDoMHZeTHZTBWjr7kNN+d8hw6RXK+VTe81Wdb20RNEJMuFnNfAmdFCPtExh9GYf4RLyrZmxXkTfG6kVAg0=',
                    'token' => 'gf40ZlB0XKpJ3Kf3bO+MfQ==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
            [
                'user' => [
                    'name' => 'Phalcon',
                    'email' => 'test6@test.com',
                    'password' => '$2y$10$qJzKY4JNRydM0wPFha01w.P4c6vjaZU48.VmNE9IiI.iFNKhdLC/6',
                    'nickname' => 'Phalcon',
                    'role' => 'interim',
                    'api_token' => '5UBhKLKlTAP37qih4wmHUb0qDj5qwsowZvpOJRY34cuNv33f7hhLTD040ptg',
                    'avator' => 'Phalcon.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/dp6sBxQKoiE:APA91bFH0jPukNUGEhEU0xjwP4UzixlYe8sfSOoU34SLOzIaKZHXYQ8tklB587LDp6svtIlQHpPjutsF8eowpEaznHno0eS12uGfLniMXU3wFMPDZilmLXndIAuBSFcwBaDyUekzy5Oe',
                    'key' => 'BN/M15luT5F9IyO/x2aGWzNnPuH02wEXvuQuJPX4Db3edGxRnh0wq+oXKRluGuZ4b9wtzVSWp/+pQqPrJXMwg1M=',
                    'token' => 'u3FOlPilfjme3+n3hMQPoA==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
            [
                'user' => [
                    'name' => 'CakePHP',
                    'email' => 'test7@test.com',
                    'password' => '$2y$10$T8fyBj28jFNibrlq4matbuKio55wp0hvGVOaVfDGPRvOcAZvZxXqi',
                    'nickname' => 'CakePHP',
                    'role' => 'interim',
                    'api_token' => 'ONYbO4cKsrmkQnKK9AXpz07BkTg80euTRxmXacTdsLnwvwYMyg9614aXBI7V',
                    'avator' => 'CakePHP.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/cUAo7Guxtnc:APA91bE5mH1EF7jK-BUGZWeyCQ5Ea9wyiH-w2QYUX9GhEksESxyJTx5ulLLdS3nu4SfUCcZ0ByxWvmGXsoxVKHqDbEAw1CTreQLUKQnnD3uwJVUUaQyPwi-DbhcQtJ6Z-gSeE49FFppy',
                    'key' => 'BCkTthk5cMO3+ooK46hJy7Ktcv83ogl6pti1ZSgvBwRx88+FaKqnaDMqGYRwmnfW1U7x5KjtTtfgUYHyaW3NYkM=',
                    'token' => 'nfNgkXZmI+GRxTr70Hj9VA==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
            [
                'user' => [
                    'name' => 'ZendFramework',
                    'email' => 'test8@test.com',
                    'password' => '$2y$10$ofDB0EnA.Qn4/2erW6gZSe7XqtYBfu9nDPs413jrkT1Jx1nrUlM9G',
                    'nickname' => 'ZendFramework',
                    'role' => 'interim',
                    'api_token' => 'lP6Vb5Qi4Fw2KLXCwL3eVPXzyzmcN9mYl5SKPb5E0AAknhOY8E7DnT0Fi0cR',
                    'avator' => 'ZendFramework.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/fPE_GxO7l2Y:APA91bExBoImILUV0mVh1jphjhpyzDtTNodpA9FiXIBYnGNkoL5arRrVTBvqaBPHLw-INLpdNHPKXhaHIBBJJs7I_sR2O4II2BsauIugiJrEdlMAr8H09_IbWyHIRsQ34zaVOPoXT980',
                    'key' => 'BLaKHM09iOUAUwxRF+WrX9rIu5424C0AqTVwQiLxhZRFdtjYB/HRQ4RiU484jQmeZFHzgurBfPi+rKEgPycLyvY=',
                    'token' => 'WwCfhFLTOc/JeznAGxqPNw==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
            [
                'user' => [
                    'name' => 'barie',
                    'email' => 'barie@test.com',
                    'password' => '$2y$10$ofDB0EnA.Qn4/2erW6gZSe7XqtYBfu9nDPs413jrkT1Jx1nrUlM9F',
                    'nickname' => 'barie',
                    'role' => 'interim',
                    'api_token' => 'lP6Vb5Qi4Fw2KLXCwL3eVPXzyzmcN9mYl5SKPb5E0AAknhOY8E7DnT0Fi0cG',
                    'avator' => 'barie.png'
                ],
                'push_notification' => [
                    'endpoint' => 'https://fcm.googleapis.com/fcm/send/cab6LfJXCCo:APA91bH8EirVpYoX_q2iKXwCu2ilt7j5pweNFGPY0s3umLiS0aRqpXW9nHwNlTchi8QqPkQvOpK9Fib_c0Ji7k7JT1IiPpIUgJi-R298ZV5oQn8vu4b1huGfxw-gv8TqFJm7UmapovrO',
                    'key' => 'BGyPvz3qYsGljwOQOiNCtzbQFT5HGE7rUvqQ+qt+tAZdhMhmQbix2AUe7tONGHUioqBsqJY0GQ/frYqjzkJUhbs=',
                    'token' => 'T6AkEuvVAjz+ZGMxxiIPUQ==',
                    'content_encoding' => 'aes128gcm',
                ]
            ],
        ];
    }
}
