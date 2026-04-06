namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@biblio.com'); 
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123')); 
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@biblio.com'); 
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        $book1 = new Book();
        $book1->setTitle('Les bons amis');
        $book1->setAuthor('Bb');
        $book1->setImage('https://m.media-amazon.com/images/I/61Jc7+hcp3L._SY466_.jpg');

        $book1->setDescription('Un conte classique sur la solidarité : un petit lapin trouve deux carottes et décide d\'en partager une avec son ami.'); 
        $book1->setStock(5);
        $book1->setCategory('Roman');
        $book1->setLanguage('fr');
        
        $manager->persist($book1);

        $book2 = new Book();
        $book2->setTitle('Madame Bovary');
        $book2->setAuthor('Gustave Flaubert');
        $book2->setImage('https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRSqAXGy4ClFAZbhvSjKu2q2OJJH0EiCbSoysH8hscy2WkjyY3nVpvIIuIVnv7ZKfeb432IfkTDah1n9qdJs9thuqujl9XNfFIU6uKPiXoeOO_z8xp5L-G02Qn9PLSbsRMm6vFqgsI&usqp=CAc.jpg');

        $book2->setDescription('Le chef-d\'œuvre de Flaubert qui raconte l\'histoire d\'Emma Bovary et sa quête désespérée de bonheur et de romantisme.');
        $book2->setStock(3);
        $book2->setCategory('Roman');
        $book2->setLanguage('fr');

        $manager->persist($book2);

        $manager->flush();

    }
}
