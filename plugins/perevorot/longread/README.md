для добавление блока:

1. Добавить папку themes/{activetheme}/partials/longread

2. добавить папку longread в своем модуле (например: page/longread)

3. добавить файл longread/longread.yaml с описанием полей каждого блока:

    richeditor:
        name: Текстовый редактор
        order: 100
        fields:
            text:
                label: текст по ширине
                span: full
                type: richeditor

    для нестандартных компонентов использовать Form Widgets конкретного модуля:

    managers:
        name: Менеджеры
        order: 200
        fields:
            managers:
                label:
                span: full
                type: Perevorot\Vacancies\FormWidgets\Managers


4. Добавить для каждого блока компонент для вывода на сайте в папку longread в модуле (например: page/vacancies/Managers.php):

    <?php namespace Perevorot\Vacancies\Longread;

    use Perevorot\Longread\Classes\LongreadComponentBase;
    use Perevorot\Vacancies\Models\Vacancy

    class Managers extends LongreadComponentBase
    {
        public function onRender()
        {
            $vacancies = Vacancy::get();

            return $this->render([
                'data' => $vacancies,
            ]);
        }
    }

    по умолчанию данные будут передаватся в шаблон themes/{activetheme}/partials/{alias компонента}


5. В рабочей модели, в которой хранятся данные, обязательно добавить behaviour для работы с изображениями

    public $implement = [
        '@Perevorot.Longread.Behaviors.LongreadAttachFiles',
    ];

    и trait Perevorot\Longread\Traits\LongreadMutators для получения доступа к мультиязычному полю

6. В файле fields.yaml для конкретной модели добавить longread_ua, longread_ru, longread_en (в зависимости от настроек языка)

    longread_ua:
        type: longread
        tab: Українська
        blocks: [text, table]
    
    если не указывать blocks то будут выведены все зарегистрированные блоки в заданной сортировке
    
7. Для добавление js или css в необходимом компоненте:

    public function init()
    {
        $this->addJs('longread/app.js');
        $this->addJs('longread/app.css');
    }