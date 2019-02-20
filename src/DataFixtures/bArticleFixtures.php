<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class bArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // fixture content
        $content = '<p class="lead drop-cap">Duis ex ad cupidatat tempor Excepteur cillum cupidatat fugiat nostrud cupidatat dolor sunt sint sit nisi est eu exercitation incididunt adipisicing veniam velit id fugiat enim mollit amet anim veniam dolor dolor irure velit commodo cillum sit nulla ullamco magna amet magna cupidatat qui labore cillum sit in tempor veniam consequat non laborum adipisicing aliqua ea nisi sint.</p>

        <p>Duis ex ad cupidatat tempor Excepteur cillum cupidatat fugiat nostrud cupidatat dolor sunt sint sit nisi est eu exercitation incididunt adipisicing veniam velit id fugiat enim mollit amet anim veniam dolor dolor irure velit commodo cillum sit nulla ullamco magna amet magna cupidatat qui labore cillum sit in tempor veniam consequat non laborum adipisicing aliqua ea nisi sint ut quis proident ullamco ut dolore culpa occaecat ut laboris in sit minim cupidatat ut dolor voluptate enim veniam consequat occaecat fugiat in adipisicing in amet Ut nulla nisi non ut enim aliqua laborum mollit quis nostrud sed sed.
        </p>

        <p>
        <img src="https://cloud.cascales.fr/index.php/apps/files_sharing/ajax/publicpreview.php?x=1920&y=505&a=true&file=wheel-1000.jpg&t=ooWU8TJ3alaXt4d&scalingup=0" sizes="(max-width: 2000px) 100vw, 2000px" alt="">
        </p>

        <h2>Large Heading</h2>

        <p>Harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus <a href="http://#">omnis voluptas assumenda est</a> id quod maxime placeat facere possimus, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et.</p>

        <blockquote><p>This is a simple example of a styled blockquote. A blockquote tag typically specifies a section that is quoted from another source of some sort, or highlighting text in your post.</p></blockquote>

        <p>Odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Nulla vitae elit libero, a pharetra augue laboris in sit minim cupidatat ut dolor voluptate enim veniam consequat occaecat fugiat in adipisicing in amet Ut nulla nisi non ut enim aliqua laborum mollit quis nostrud sed sed.</p>

        <h3>Smaller Heading</h3>

        <p>Dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Nulla vitae elit libero, a pharetra augue laboris in sit minim cupidatat ut dolor voluptate enim veniam consequat occaecat fugiat in adipisicing in amet Ut nulla nisi non ut enim aliqua laborum mollit quis nostrud sed sed.

<pre>
<div class="ui top right attached label code">Code</div>
<code>
//Hello test
code {
    font-size: 1.4rem;
    margin: 0 .2rem;
    padding: .2rem .6rem;
    white-space: nowrap;
    background: #F1F1F1;
    border: 1px solid #E1E1E1;
    border-radius: 3px;
}
</code>
</pre>

        <p>Odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa.</p>

        <ul>
            <li>Donec nulla non metus auctor fringilla.
                <ul>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                </ul>
            </li>
            <li>Donec nulla non metus auctor fringilla.</li>
            <li>Donec nulla non metus auctor fringilla.</li>
        </ul>

        <p>Odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Nulla vitae elit libero, a pharetra augue laboris in sit minim cupidatat ut dolor voluptate enim veniam consequat occaecat fugiat in adipisicing in amet Ut nulla nisi non ut enim aliqua laborum mollit quis nostrud sed sed.</p>';

        // ARTICLE HTML TEST
        $article = new Article();
        $article->setTitle("Ouverture de TitoCode")
                ->setPrivateThumb("assets/img/articles/public/opened.jpg")
                ->setPublicThumb("assets/img/articles/public/opened.jpg")
                ->setContent('<blockquote><p>L\'aventure, c\'est d\'abord l\'ouverture aux autres.</p></blockquote>

<p>
Titocode ouvre désormais ses portes au web mais ce n\'est qu\'un début. Le site n\'est pas encore dans sa forme finale. Vous pouvez évidement avoir accès aux tutoriels présents sur le site mais d\'autres choses sont à venir.
</p>

<p>
Ce que nous prévoyons pour l\'avenir :
<ul>
<li>
Un système d\'inscription et une interface dédiée aux utilisateurs inscrit sur Titocode qui auront accès à des avantages par rapport aux visiteurs.
</li>
<li>
Des espaces commentaires pour chaque article si jamais vous avez un problème pendant le suivi d\'un tutoriel ou même si vous souhaitez déposer un avis sur l\'un de nos articles.
</li>
<li>
Une boutique de morceaux de code et de templates HTML et Symfony4.
</li>
</ul>
</p>

<p>
Si toutefois vous souhaitez nous contacter, un formulaire de contact est mis à votre disposition dans l\'onglet <a href="https://titocode.cascales.fr/contact">Contactez-nous</a> !
</p>

<p>
L\'équipe TitoCode.
</p>')
                ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', "2018-11-30 13:25:08"))
                ->setTags(array("Mise en ligne", "TitoCode"))
                ->setType("standard")
                ->setVue(1)
                ->setCategory($this->getReference(aCategoryFixtures::CAT_HTML));
        $manager->persist($article);

        // 2 eme ARTICLE HTML TEST
        $article = new Article();
        $article->setTitle("Mon 2eme article")
                ->setPrivateThumb("assets/img/thumbs/single/standard/standard-1000.jpg")
                ->setPublicThumb("assets/img/thumbs/post/watch-400.jpg")
                ->setContent($content)
                ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')))
                ->setTags(array("HTML", "Codeur"))
                ->setType("standard")
                ->setVue(1)
                ->setCategory($this->getReference(aCategoryFixtures::CAT_HTML));
        $manager->persist($article);

        // 3 eme ARTICLE SYMFONY TEST
        $article = new Article();
        $article->setTitle("Mon 3eme article")
                ->setPrivateThumb("assets/img/thumbs/single/standard/standard-1000.jpg")
                ->setPublicThumb("assets/img/thumbs/post/tulips-400.jpg")
                ->setContent($content)
                ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')))
                ->setTags(array("Symfony", "Framework"))
                ->setType("standard")
                ->setVue(1)
                ->setCategory($this->getReference(aCategoryFixtures::CAT_SYMFONY));
        $manager->persist($article);

        $manager->flush();
    }
}
