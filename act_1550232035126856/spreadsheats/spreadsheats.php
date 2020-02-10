<?php
	require  'vendor/autoload.php';
	use Google\Spreadsheet\DefaultServiceRequest;
	use Google\Spreadsheet\ServiceRequestFactory;
	
	putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/my_secret2.json');
			/*  SEND TO GOOGLE SHEETS */
			 $client = new Google_Client;
				try{
					$client->useApplicationDefaultCredentials();
				  $client->setApplicationName("Something to do with my representatives");
					$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
				   if ($client->isAccessTokenExpired()) {
						$client->refreshTokenWithAssertion();
					}

					$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
					ServiceRequestFactory::setInstance(
						new DefaultServiceRequest($accessToken)
					);
				   // Get our spreadsheet
					$spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
						->getSpreadsheetFeed()
						->getByTitle('novbud_pl_leads');

					// Get the first worksheet (tab)
					$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
					$worksheet = $worksheets[0];
					
					// Запишим переменные
					$insert_perem = 1; // Чтобы избежать двух listFeed будем использовать обновляемый параметр
					$data = date_create('now')->format('Y-m-d'); // Дата в нужном формате
					
					$listFeed = $worksheet->getListFeed();
					foreach ( $listFeed->getEntries() as $entry ) {
						$lastdate = $entry->getValues()['date']; //Получим значение крайней даты
						if($entry == end($listFeed->getEntries())) {
							if ($lastdate  == $data) {
								echo ' Уже есть, обновляем и записываем переменную на обработку (insert_perem = 0);';
								$insert_perem = 0;
								if ($lastdate === $data) {
									$entry->update(array_merge($entry->getValues(), [
										'impressions' => "'".$impressions,
										'objective' => "'".$objective,
										'actions' => "'".$actions,
										'date' => $data
									]));
								}
							}
						}
					}
					if($insert_perem == 1) {
						echo ' Создаем строку.';
						$listFeed->insert([
							'impressions' => "'".$impressions,
							'objective' => "'".$objective,
							'actions' => "'".$actions,
							'date' => date_create('now')->format('Y-m-d')
						]);
					}
				}catch(Exception $e){
				  echo $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getCode;
				}
