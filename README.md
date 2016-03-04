# JobQueueBundle

Symfony bundle using [Aureja/JobQueue](https://github.com/Aureja/JobQueue) for job queues management.

## Installation

**Step 1**. Install via [Composer](https://getcomposer.org/)

```
composer require aureja/job-queue-bundle "dev-master"
```

**Step 2**. Add to `AppKernel.php`

```php
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
             // ...
             new Aureja\Bundle\JobQueueBundle\AurejaJobQueueBundle(),
             // ...
        ];
    }
}
```

**Step 3**. Define your entities by extending Aureja models or implementing the interfaces

```php
<?php
// src/Acme/YourBundle/Entity/JobReport.php

namespace Acme\YourBundle\Entity;

use Aureja\JobQueue\Model\JobReport as BaseJobReport;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="aureja_job_report")
 */
class JobReport extends BaseJobReport
{
    /**
     * {@inheritdoc}
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * {@inheritdoc}
     *
     * @ORM\ManyToOne(targetEntity="Acme\YourBundle\Entity\JobConfiguration")
     * @ORM\JoinColumn(name="configuration_id", nullable=false, onDelete="CASCADE")
     */
    protected $configuration;
    
    // Your custom logic if needed.
}

```

```php
<?php
// src/Acme/YourBundle/Entity/JobConfiguration.php

namespace Acme\YourBundle\Entity;

use Aureja\JobQueue\Model\JobConfiguration as BaseJobConfiguration;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="aureja_job_configuration")
 */
class JobConfiguration extends BaseJobConfiguration
{
    /**
     * {@inheritdoc}
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    // Your custom logic if needed.
}

```
**Step 4**. [Update your database schema](http://symfony.com/doc/current/book/doctrine.html#creating-the-database-tables-schema)

**Step 5**. Configure via `app/config/config.yml`

```yml
# app/config/config.yml

aureja_job_queue:
    db_driver: orm
    class:
        model:
            job_configuration: Acme\YourBundle\Entity\JobConfiguration
            job_report: Acme\YourBundle\Entity\JobReport
            
    # Define queues as an array or as a string with values separated by a comma.
    queues:
        - default
```

**Step 6**. Import AurejaJobQueue routing files

```yml
# app/config/routing.yml

aureja_job_queue:
    resource: "@AurejaJobQueueBundle/Resources/config/routing.xml"
```

**Step 7**. Register cronjob to be executed every minute

```
* * * * * php app/console aureja:job-queue:run
```
