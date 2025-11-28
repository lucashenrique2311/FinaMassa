<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioInicialSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Verifica se a tabela CAD_CLIENTE existe (tabela antiga)
        if ($db->tableExists('CAD_CLIENTE')) {
            // Verifica se já existe um usuário admin
            $builder = $db->table('CAD_CLIENTE');
            $existe = $builder->where('EMAIL', 'admin@sistema.com')->get()->getRow();
            
            if (!$existe) {
                $data = [
                    'EMAIL' => 'admin@sistema.com',
                    'RAZAO_SOCIAL' => 'Administrador do Sistema',
                    'SENHA' => sha1('admin123'), // Senha: admin123
                    'ADMIN' => '1',
                    'EXPIRADO' => 'N',
                ];
                
                $builder->insert($data);
                echo "✅ Usuário admin criado na tabela CAD_CLIENTE\n";
                echo "   Email: admin@sistema.com\n";
                echo "   Senha: admin123\n";
            } else {
                echo "ℹ️  Usuário admin já existe na tabela CAD_CLIENTE\n";
            }
        }
        
        // Verifica se a tabela usuarios existe (nova estrutura)
        if ($db->tableExists('usuarios')) {
            // Verifica se já existe um usuário admin
            $builder = $db->table('usuarios');
            $existe = $builder->where('email', 'admin@sistema.com')->get()->getRow();
            
            if (!$existe) {
                $data = [
                    'id_cliente' => null, // Admin do sistema
                    'nome' => 'Administrador do Sistema',
                    'email' => 'admin@sistema.com',
                    'senha' => sha1('admin123'), // Senha: admin123
                    'telefone' => null,
                    'ativo' => 1,
                    'admin' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                
                $builder->insert($data);
                echo "✅ Usuário admin criado na tabela usuarios\n";
                echo "   Email: admin@sistema.com\n";
                echo "   Senha: admin123\n";
            } else {
                echo "ℹ️  Usuário admin já existe na tabela usuarios\n";
            }
        }
    }
}

