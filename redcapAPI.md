You can find all methods to interact with REDCap in Services/RedCapApiService.php.

I should not directly use any method of RedCapProject.php but only use the service.
```php
exportPatient(Collection $Patients) : void
exportMedicalCase(Collection $MedicalCase) : void
```

You can add any method you want/need in the RedCapApiService.php. Every api method on the RedCapApiService.php may throw a RedCapApiServiceException.
This should be catch everytime service's methods are call.

RedCapApiService.php define instances of RedCapProject.php for the api call. Every redcap project must be define in a different variable in 
RedCapApiService.php Controller.

```php
  /**
   * @var RedCapProject
   */
  protected $projectPersonalData;

    ...
 
  /**
   * RedCapContactApiService constructor.
   * @param RedCapProject $project
   * @throws PhpCapException
   */
  public function __construct()
  {

    $this->projectPersonalData = $this->getRedCapProject(
      Config::get('redcap.identifiers.api_url_personal_data'),
      Config::get('redcap.identifiers.api_token_personal_data')
    );

    ...
```

RedCapProject.php is instanciated with getRedCapProject(). You should not use .env as parameter (always use config)

Every REDCap project must have a valide url/token configurate in .env
```
REDCAP_IDENTIFIERS_API_URL_PERSONAL_DATA=[API URL]
REDCAP_IDENTIFIERS_API_TOKEN_PERSONAL_DATA=[TOKEN]
```


All REDCap fields, project's url and token are configurate in laravel with conf file : config/redcap.php
```php
'identifiers' => [
      'api_url_personal_data' => env('REDCAP_IDENTIFIERS_API_URL_PERSONAL_DATA', ''),
      'api_token_personal_data' => env('REDCAP_IDENTIFIERS_API_TOKEN_PERSONAL_DATA', ''),
       ......
      'patient' => [
        'id' => 'record_id',
        'lastName' => 'last_name',
        'firstName' => 'first_name',
        ],
      'medicalCase' => [
        'id' => 'record_id',
        'patientID' => 'patient_id',
      ],
      'followup' => [
        'id' => 'record_id',
        'patient_id' => 'patient_id',
      ]
    ],
```


A example of mapping between REDCap fields and laravel model before pushing to redcap
```php
      foreach ($patients as $patient) {
        // this is the mapping between redcap field (define in config) and patient model
        // has to be update everytime we add a new field in redcap
        $datas[$patient['id']] = [
          Config::get('redcap.identifiers.patient.id') => $patient['id'],
          Config::get('redcap.identifiers.patient.firstName') => $patient['first_name'],
          Config::get('redcap.identifiers.patient.lastName') => $patient['last_name'],
        ];
      }
```

Example : export all Patients and MedicalCases to REDCap
```php
    $redCapApiService = new RedCapApiService();
    $patients = Patient::All();
    $medicalCases = MedicalCase::All();
    $redCapApiService->exportPatient($patients);
    $redCapApiService->exportMedicalCase($medicalCases);
```
