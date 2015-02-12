# alterdataToCsv

A  integração se dá 3 etapas: 

	- Exportação por parte do sistema da alterdata
	- Tratamento dos dados pelo sistema no pdv que envia por e-mail o cadastro em formato CSV;
	- Importação manual

Configurando a integração 

Exportação do cadastro em todos os PDVS 

- Configurar o sistema para exportar toda segunda-feira em cada PDV
- Acessar "Configurações e Manutenção / Importação/Exportação / Integração Shop x Palm
- Clique em "Agendar Exportação" para configurar quando deve ser gerado uma nova remessa.

Instalando o sistema de tratamento de dados e envio por e-mail:

	- Baixe o programa alterdataToCSV
	- Descompacte na raiz
	- Altere o nome da loja no arquivo config.php

			Ex:
			
				De: 
					$PDV_NAME	= "nome-da-loja-1";
				Para:

					$PDV_NAME	= "dona-madonna-1";
	

	- Agendar o tratamento de dados para executar 1 hora depois da geração da exportação:

			Frequência

				1 vez ao dia

			Caminho a ser executado

				“c:/alterdataToCSV/run.bat”


Passo a passo 

https://technet.microsoft.com/pt-br/library/cc721931.aspx 	
