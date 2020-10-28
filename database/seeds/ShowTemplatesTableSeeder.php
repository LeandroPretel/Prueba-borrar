<?php

use App\ShowClassification;
use App\ShowTemplate;
use Illuminate\Database\Seeder;
use Savitar\Files\SavitarFile;

class ShowTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        // $account = Account::where('email', 'promotor@redentradas.es')->first();
        $showTemplate = new ShowTemplate();
        $showTemplate->name = 'Gran concierto';
        $showTemplate->webName = 'Gran concierto';
        $showTemplate->ticketName = 'Gran concierto';
        $showTemplate->description = '<p><strong>Lorem ipsum dolor sit amet</strong>, nonumes voluptatum mel ea, cu case ceteros cum. Novum commodo malorum vix ut. Dolores consequuntur in ius, sale electram dissentiunt quo te. Cu duo omnes invidunt, eos eu mucius fabellas. Stet facilis ius te, quando voluptatibus eos in. Ad vix mundi alterum, integre urbanitas intellegam vix in.</p>
<p>Eum facete intellegat ei, ut mazim melius usu. Has elit simul primis ne, regione minimum id cum. Sea deleniti dissentiet ea. Illud mollis moderatius ut per, at qui ubique populo. Eum ad cibo legimus, vim ei quidam fastidii.</p>
<p>Quo debet vivendo ex. Qui ut admodum senserit partiendo. Id adipiscing disputando eam, sea id magna pertinax concludaturque. Ex ignota epicurei quo, his ex doctus delenit fabellas, erat timeam cotidieque sit in. Vel eu soleat voluptatibus, cum cu exerci mediocritatem. Malis legere at per, has brute putant animal et, in consul utamur usu.</p>
<p>Te has amet modo perfecto, te eum mucius conclusionemque, mel te erat deterruisset. Duo ceteros phaedrum id, ornatus postulant in sea. His at autem inani volutpat. Tollit possit in pri, platonem persecuti ad vix, vel nisl albucius gloriatur no.</p>
<p>Ea duo atqui incorrupte, sed rebum regione suscipit ex, mea ex dicant percipit referrentur. Dicat luptatum constituam vix ut. His vide platonem omittantur id, vel quis vocent an. Ad pro inani zril omnesque. Mollis forensibus sea an, vim habeo adipisci contentiones ad, tale autem graecis ne sit.</p>';
        $showTemplate->showClassification()->associate(ShowClassification::first());
        // $showTemplate->additionalInfo = '';
        $showTemplate->duration = 120;
        $showTemplate->break = 0;
        $showTemplate->save();

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQQaig3zAL63pN2KykhxzYJ2UVYp8owMF0haxXe8hkGlykVwv9qjg';
        $file->extension = 'png';
        $file->pages = 1;
        $showTemplate->files()->save($file);

        $file = new SavitarFile();
        $file->category = 'image';
        $file->name = 'Imagen2.png';
        $file->size = 113131;
        $file->path = '';
        $file->url = 'http://img2.rtve.es/i/?w=1600&i=1540117231135.jpg';
        $file->extension = 'jpg';
        $file->pages = 1;
        $showTemplate->files()->save($file);


        $showTemplate = new ShowTemplate();
        $showTemplate->name = 'El festival';
        $showTemplate->webName = 'El festival';
        $showTemplate->ticketName = 'El festival';
        $showTemplate->description = '<p><strong>Lorem ipsum dolor sit amet</strong>, nonumes voluptatum mel ea, cu case ceteros cum. Novum commodo malorum vix ut. Dolores consequuntur in ius, sale electram dissentiunt quo te. Cu duo omnes invidunt, eos eu mucius fabellas. Stet facilis ius te, quando voluptatibus eos in. Ad vix mundi alterum, integre urbanitas intellegam vix in.</p>
<p>Eum facete intellegat ei, ut mazim melius usu. Has elit simul primis ne, regione minimum id cum. Sea deleniti dissentiet ea. Illud mollis moderatius ut per, at qui ubique populo. Eum ad cibo legimus, vim ei quidam fastidii.</p>
<p>Quo debet vivendo ex. Qui ut admodum senserit partiendo. Id adipiscing disputando eam, sea id magna pertinax concludaturque. Ex ignota epicurei quo, his ex doctus delenit fabellas, erat timeam cotidieque sit in. Vel eu soleat voluptatibus, cum cu exerci mediocritatem. Malis legere at per, has brute putant animal et, in consul utamur usu.</p>
<p>Te has amet modo perfecto, te eum mucius conclusionemque, mel te erat deterruisset. Duo ceteros phaedrum id, ornatus postulant in sea. His at autem inani volutpat. Tollit possit in pri, platonem persecuti ad vix, vel nisl albucius gloriatur no.</p>
<p>Ea duo atqui incorrupte, sed rebum regione suscipit ex, mea ex dicant percipit referrentur. Dicat luptatum constituam vix ut. His vide platonem omittantur id, vel quis vocent an. Ad pro inani zril omnesque. Mollis forensibus sea an, vim habeo adipisci contentiones ad, tale autem graecis ne sit.</p>';
        $showTemplate->showClassification()->associate(ShowClassification::first());
        // $showTemplate->additionalInfo = '';
        $showTemplate->duration = 120;
        $showTemplate->break = 0;
        $showTemplate->save();

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS8VyueMw0QSExpxpd2WvUntTV_skQR-hRdbY3-EMvk342StwjjxA';
        $file->extension = 'png';
        $file->pages = 1;
        $showTemplate->files()->save($file);


        $showTemplate = new ShowTemplate();
        $showTemplate->name = 'Una noche';
        $showTemplate->webName = 'Una noche';
        $showTemplate->ticketName = 'Una noche';
        $showTemplate->showClassification()->associate(ShowClassification::first());
        $showTemplate->description = '<p><strong>Lorem ipsum dolor sit amet</strong>, nonumes voluptatum mel ea, cu case ceteros cum. Novum commodo malorum vix ut. Dolores consequuntur in ius, sale electram dissentiunt quo te. Cu duo omnes invidunt, eos eu mucius fabellas. Stet facilis ius te, quando voluptatibus eos in. Ad vix mundi alterum, integre urbanitas intellegam vix in.</p>
<p>Eum facete intellegat ei, ut mazim melius usu. Has elit simul primis ne, regione minimum id cum. Sea deleniti dissentiet ea. Illud mollis moderatius ut per, at qui ubique populo. Eum ad cibo legimus, vim ei quidam fastidii.</p>
<p>Quo debet vivendo ex. Qui ut admodum senserit partiendo. Id adipiscing disputando eam, sea id magna pertinax concludaturque. Ex ignota epicurei quo, his ex doctus delenit fabellas, erat timeam cotidieque sit in. Vel eu soleat voluptatibus, cum cu exerci mediocritatem. Malis legere at per, has brute putant animal et, in consul utamur usu.</p>
<p>Te has amet modo perfecto, te eum mucius conclusionemque, mel te erat deterruisset. Duo ceteros phaedrum id, ornatus postulant in sea. His at autem inani volutpat. Tollit possit in pri, platonem persecuti ad vix, vel nisl albucius gloriatur no.</p>
<p>Ea duo atqui incorrupte, sed rebum regione suscipit ex, mea ex dicant percipit referrentur. Dicat luptatum constituam vix ut. His vide platonem omittantur id, vel quis vocent an. Ad pro inani zril omnesque. Mollis forensibus sea an, vim habeo adipisci contentiones ad, tale autem graecis ne sit.</p>';
        // $showTemplate->additionalInfo = '';
        $showTemplate->duration = 120;
        $showTemplate->break = 0;
        $showTemplate->save();

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcGSVYLXFixn5EtHFs3FfdPiN47BTClj_SRYmkxLY4z_bhhjcE';
        $file->extension = 'png';
        $file->pages = 1;
        $showTemplate->files()->save($file);


        $showTemplate = new ShowTemplate();
        $showTemplate->name = 'Musical';
        $showTemplate->webName = 'Musical';
        $showTemplate->ticketName = 'Musical';
        $showTemplate->description = '<p><strong>Lorem ipsum dolor sit amet</strong>, nonumes voluptatum mel ea, cu case ceteros cum. Novum commodo malorum vix ut. Dolores consequuntur in ius, sale electram dissentiunt quo te. Cu duo omnes invidunt, eos eu mucius fabellas. Stet facilis ius te, quando voluptatibus eos in. Ad vix mundi alterum, integre urbanitas intellegam vix in.</p>
<p>Eum facete intellegat ei, ut mazim melius usu. Has elit simul primis ne, regione minimum id cum. Sea deleniti dissentiet ea. Illud mollis moderatius ut per, at qui ubique populo. Eum ad cibo legimus, vim ei quidam fastidii.</p>
<p>Quo debet vivendo ex. Qui ut admodum senserit partiendo. Id adipiscing disputando eam, sea id magna pertinax concludaturque. Ex ignota epicurei quo, his ex doctus delenit fabellas, erat timeam cotidieque sit in. Vel eu soleat voluptatibus, cum cu exerci mediocritatem. Malis legere at per, has brute putant animal et, in consul utamur usu.</p>
<p>Te has amet modo perfecto, te eum mucius conclusionemque, mel te erat deterruisset. Duo ceteros phaedrum id, ornatus postulant in sea. His at autem inani volutpat. Tollit possit in pri, platonem persecuti ad vix, vel nisl albucius gloriatur no.</p>
<p>Ea duo atqui incorrupte, sed rebum regione suscipit ex, mea ex dicant percipit referrentur. Dicat luptatum constituam vix ut. His vide platonem omittantur id, vel quis vocent an. Ad pro inani zril omnesque. Mollis forensibus sea an, vim habeo adipisci contentiones ad, tale autem graecis ne sit.</p>';
        $showTemplate->showClassification()->associate(ShowClassification::first());
        // $showTemplate->additionalInfo = '';
        $showTemplate->duration = 120;
        $showTemplate->break = 0;
        $showTemplate->save();

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhSDJtLLH6GkPtr1YtxvLQZwtsYo-E9psqrzh1GMVQI1wWUE63gA';
        $file->extension = 'png';
        $file->pages = 1;
        $showTemplate->files()->save($file);

        $showTemplate = new ShowTemplate();
        $showTemplate->name = 'Concierto';
        $showTemplate->webName = 'Concierto';
        $showTemplate->ticketName = 'Concierto';
        $showTemplate->description = '<p><strong>Lorem ipsum dolor sit amet</strong>, nonumes voluptatum mel ea, cu case ceteros cum. Novum commodo malorum vix ut. Dolores consequuntur in ius, sale electram dissentiunt quo te. Cu duo omnes invidunt, eos eu mucius fabellas. Stet facilis ius te, quando voluptatibus eos in. Ad vix mundi alterum, integre urbanitas intellegam vix in.</p>
<p>Eum facete intellegat ei, ut mazim melius usu. Has elit simul primis ne, regione minimum id cum. Sea deleniti dissentiet ea. Illud mollis moderatius ut per, at qui ubique populo. Eum ad cibo legimus, vim ei quidam fastidii.</p>
<p>Quo debet vivendo ex. Qui ut admodum senserit partiendo. Id adipiscing disputando eam, sea id magna pertinax concludaturque. Ex ignota epicurei quo, his ex doctus delenit fabellas, erat timeam cotidieque sit in. Vel eu soleat voluptatibus, cum cu exerci mediocritatem. Malis legere at per, has brute putant animal et, in consul utamur usu.</p>
<p>Te has amet modo perfecto, te eum mucius conclusionemque, mel te erat deterruisset. Duo ceteros phaedrum id, ornatus postulant in sea. His at autem inani volutpat. Tollit possit in pri, platonem persecuti ad vix, vel nisl albucius gloriatur no.</p>
<p>Ea duo atqui incorrupte, sed rebum regione suscipit ex, mea ex dicant percipit referrentur. Dicat luptatum constituam vix ut. His vide platonem omittantur id, vel quis vocent an. Ad pro inani zril omnesque. Mollis forensibus sea an, vim habeo adipisci contentiones ad, tale autem graecis ne sit.</p>';
        $showTemplate->showClassification()->associate(ShowClassification::first());
        // $showTemplate->additionalInfo = '';
        $showTemplate->duration = 180;
        $showTemplate->break = 30;
        $showTemplate->save();

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRtsgvb5tsN-O_hfzvpjA8J_eTUGufg-na85287iYGTU6cfQod';
        $file->extension = 'png';
        $file->pages = 1;
        $showTemplate->files()->save($file);
    }
}
