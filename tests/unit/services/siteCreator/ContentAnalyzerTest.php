<?php

namespace tests\unit\services\siteCreator;

use app\services\siteCreator\ContentAnalyzer;

class ContentAnalyzerTest extends \Codeception\Test\Unit
{
    /**
     * @var ContentAnalyzer
     */
    private $contentAnalyzer;

    protected function setUp()
    {
        $this->contentAnalyzer = \Yii::$container->get(ContentAnalyzer::class);
        return parent::setUp();
    }

    public function testLongWordsSimple(): void
    {
        $result = $this->contentAnalyzer->cleanFromLongWords('many text here another many text here manyPeople likeCamelCaseD words BrrrAaaH USSR LLLLove LLLLoveMe and here many text here');
        $this->assertEquals('many text here another many text here many people like camel caseD words brrr aaaH USSR LLLLove LLLlove me and here many text here', $result);
    }

    public function testLongWords(): void
    {
        $inputText = <<<TEXT
CelebritiesActorsActressesComediansModelsMusiciansAthletesBusiness PeoplePoliticiansMedia
TEXT;
        $result = $this->contentAnalyzer->cleanFromLongWords($inputText);
        $this->assertEquals('celebrities actors actresses comedians models musicians athletes business people politicians media', $result);
    }

    /**
     * @dataProvider textsVariants
     * @param string $text
     * @param bool $isEnglish
     */
    public function testCheckContentIsNotEnglish(string $text, bool $isEnglish): void
    {
        $result = $this->contentAnalyzer->checkContentIsEnglish($text);
        $this->assertEquals($isEnglish, $result);
    }

    public function textsVariants(): array
    {
        return [
            ['привет дядя федор groovy', false],
            ['hello mr jack', true],
            ['hello mr jack я Вася', false],
            ['привет, я пузырь', false],
            ['many many people loves иван because he\'s famous brraaaah', true],
            [<<<TEXT
Мы обратили внимание, что Вы используете неподдерживаемый браузер. Веб-сайт TripAdvisor может отображаться неправильно.Поддерживаются следующие браузеры:Windows: Internet Explorer, Mozilla Firefox, Google Chrome. Mac: Safari.            Сингапур     Туризм в Сингапуре     Отели Сингапура     B&B/мини-отели в Сингапуре     Турпакеты в Сингапур     Авиабилеты в Сингапур     Рестораны Сингапура     Развлечения Сингапура     Сингапур: магазины     Форум путешественников о Сингапуре     Фото Сингапура     Карта Сингапура        Все отели Сингапура   Спецпредложения отелей в Сингапуре   Горящие туры в Сингапуре    По типу отеля    Хостелы: Сингапур   Сингапур: кемпинги   Сингапур: отели бизнес-класса   Лучшие семейные отели Сингапура   Сингапур: спа-курорты   Отели класса "люкс" в Сингапуре   Романтические отели Сингапура   Отели у пляжа: Сингапур   Казино: Сингапур   Курорты: Сингапур      По классу отеля    5–звездочные отели в г. Сингапур   4–звездочные отели в г. Сингапур   3–звездочные отели в г. Сингапур      По сети отелей     Отели Far East Collection в Сингапуре    Отели InterContinental в Сингапуре    Отели Six Senses Hotels Resorts Spas в Сингапуре    Отели Yotel в Сингапуре    Отели Meritus в Сингапуре    Отели Ascott The Residence в Сингапуре    Отели Под управлением AccorHotels в Сингапуре    Отели Preferred Hotels & Resorts в Сингапуре    Отели Hyatt в Сингапуре    Отели Parkroyal в Сингапуре    Отели Holiday Inn в Сингапуре    Отели Rendezvous Hotels в Сингапуре      Популярные удобства    Oтели с бассейном в Сингапуре   Отели с бесплатной парковкой в Сингапуре   Отели в Сингапуре, где разрешено проживание домашних животных      Популярные районы    Отели Central Area/City Area   Отели Downtown Core/Downtown Singapore   Отели Marina Bay   Отели Bayfront   Отели City Hall   Отели Marina Centre   Отели Singapore River/Riverside   Отели Colonial District/Civic District   Отели Rochor   Отели Orchard      Популярные категории в Сингапуре    Апарт-Отели в Сингапуре   Дешевое Жилье в Сингапуре   Дизайнерские Отели в Сингапуре   Отели Класса Люкс в Сингапуре   Тематические Отели в Сингапуре   Свадебные Отели в Сингапуре   Отели Все Включено в Сингапуре   Курорты Для Отдыха С Детьми в Сингапуре   Пляжные Курорты в Сингапуре   Отели Сингапура с номерами для курящих      Достопримечательности поблизости    Отели рядом - Gardens by the Bay   Отели рядом - Singapore Mass Rapid Transit (SMRT)   Отели рядом - Сингапурский зоопарк   Отели рядом - Ботанические сады Сингапура   Отели рядом - Сингапурское колесо обозрения Flyer   Отели рядом - Sands SkyPark   Отели рядом - Cloud Forest   Отели рядом - Китайский квартал   Отели рядом - Национальный сад орхидей Сингапура   Отели рядом - Орчард-роуд           СингапурПоиск "" ОпубликоватьПоездкиСовет. Вы можете найти все места, которые Вы сохранили, в разделе "Мои поездки".  ВходящиеВходящие Показать все Войдите в систему, чтобы получить новости о поездке и отправить сообщения другим путешественникам. ПрофильСингапурОтели Развлечения Рестораны Авиабилеты Покупки Отпускные турпакеты Прокат автомобилей Форум Авиакомпании Лучшее в 2019 Справочный центр Войти Зарегистрироваться   Вы недавно просмотрели Бронирования Входящие Еще Справочный центр    Beautiful Buildings in Singapore - Изображение Singapore Street Shots, Сингапур  Азия&nbsp;&nbsp;Сингапур&nbsp;&nbsp;Сингапур&nbsp;&nbsp;Развлечения в Сингапуре&nbsp;&nbsp;Снимки Singapore Street Shots      Фотография: “Beautiful Buildings in Singapore”
TEXT
            ,false],
            [<<<TEXT
Перейти кРазделы этой СтраницыСправочный центр специальных возможностейНажмите alt и / одновременно, чтобы открыть это менюFacebookЭлектронный адрес или номер телефонаПарольЗабыли аккаунт?ЗарегистрироватьсяОткрыть Страницу «Rutgers University—New Brunswick» на FacebookВходилиСоздать аккаунтОткрыть Страницу «Rutgers University—New Brunswick» на FacebookВходЗабыли аккаунт?илиСоздать аккаунтНе сейчасRutgers University—New BrunswickВидео6 of the Most Beautiful Buildings at Rutgers–New Brunswick
TEXT
            ,false],
        ];
    }
}