<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Widgets\PopularPostsWidget;
use App\Filament\Widgets\PostsChartWidget;
use App\Models\Post;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PopularPostsWidgetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'ViewAny:Post', 'View:Post', 'Create:Post', 'Update:Post', 'ViewAll:Post',
            'View:PopularPostsWidget',
        ] as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web'])
            ->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'author', 'guard_name' => 'web'])
            ->syncPermissions(['ViewAny:Post', 'View:Post', 'Create:Post', 'Update:Post']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole('super_admin');
    }

    public function test_lists_top_five_published_posts_by_views(): void
    {
        $this->actingAs($this->admin());

        $ranked = collect([900, 700, 500, 300, 100])
            ->map(fn (int $views): Post => Post::factory()->create(['views_count' => $views]));
        $sixth = Post::factory()->create(['views_count' => 50]);
        $draft = Post::factory()->draft()->create(['views_count' => 5000]);
        $scheduled = Post::factory()->create(['published_at' => now()->addWeek(), 'views_count' => 5000]);
        $unread = Post::factory()->create(['views_count' => 0]);

        Livewire::test(PopularPostsWidget::class)
            ->assertCanSeeTableRecords($ranked, inOrder: true)
            ->assertCanNotSeeTableRecords([$sixth, $draft, $scheduled, $unread]);
    }

    public function test_shows_empty_state_when_no_post_has_been_read(): void
    {
        $this->actingAs($this->admin());

        Post::factory()->count(2)->create();

        Livewire::test(PopularPostsWidget::class)
            ->assertSee('Belum ada data pembaca');
    }

    public function test_admin_row_links_to_edit_page_and_actions_point_to_the_right_urls(): void
    {
        $this->actingAs($this->admin());

        $post = Post::factory()->create(['views_count' => 10]);

        Livewire::test(PopularPostsWidget::class)
            ->assertSee(PostResource::getUrl('edit', ['record' => $post]), false)
            ->assertActionHasUrl(TestAction::make('buka_artikel')->table($post), route('blog.show', $post->slug))
            ->assertActionShouldOpenUrlInNewTab(TestAction::make('buka_artikel')->table($post))
            ->assertActionVisible(TestAction::make('lihat_semua')->table())
            ->assertActionHasUrl(
                TestAction::make('lihat_semua')->table(),
                PostResource::getUrl('index', ['sort' => 'views_count:desc']),
            );
    }

    public function test_user_who_cannot_edit_the_post_gets_no_edit_link(): void
    {
        $author = User::factory()->create()->assignRole('author');
        $author->givePermissionTo('View:PopularPostsWidget');
        $this->actingAs($author);

        $othersPost = Post::factory()->create(['views_count' => 10]);

        Livewire::test(PopularPostsWidget::class)
            ->assertCanSeeTableRecords([$othersPost])
            ->assertDontSee(PostResource::getUrl('edit', ['record' => $othersPost]), false);
    }

    public function test_visibility_follows_the_widget_permission(): void
    {
        $this->actingAs($this->admin());
        $this->assertTrue(PopularPostsWidget::canView());

        $this->actingAs(User::factory()->create()->assignRole('author'));
        $this->assertFalse(PopularPostsWidget::canView());
    }

    public function test_widget_sits_next_to_the_content_activity_chart(): void
    {
        $widgets = (new Dashboard)->getWidgets();

        $this->assertSame(
            array_search(PostsChartWidget::class, $widgets, true) + 1,
            array_search(PopularPostsWidget::class, $widgets, true),
        );
    }
}
