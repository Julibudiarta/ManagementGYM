<?php

namespace App\Providers\Filament;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CheckOutResource;
use App\Filament\Resources\MemberResource;
use App\Filament\Resources\MembershipResource;
use App\Filament\Resources\MemberSubscriptionsResource;
use App\Filament\Resources\ProductssResource;
use App\Filament\Resources\ReportResource;
use App\Filament\Resources\SchedulingResource;
use App\Filament\Resources\TransactionResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])

            //navigation Group
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        ...Dashboard::getNavigationItems(),
                    ])
                    ->groups([
                        NavigationGroup::make('Membership')  
                        ->items([
                                ...MembershipResource::getNavigationItems(),
                                ...MemberResource::getNavigationItems(),
                                ...MemberSubscriptionsResource::getNavigationItems()
                            ]),
                        NavigationGroup::make('Transaction')
                            
                            ->collapsed()
                            ->items([
                                ...TransactionResource::getNavigationItems(),  
                            ]),
                        NavigationGroup::make('Product')
                            ->collapsed()
                            ->items([
                                ...CategoryResource::getNavigationItems(), 
                                ...ProductssResource::getNavigationItems() 
                            ]),
                        NavigationGroup::make('Check-in / Check-out')
                          
                            ->collapsed()
                            ->items([
                                ...SchedulingResource::getNavigationItems(), 
                                ...CheckOutResource::getNavigationItems() 
                            ]),
                         NavigationGroup::make('Report')
                          
                            ->collapsed()
                            ->items([
                                ...ReportResource::getNavigationItems(),
                            ]),
                        ]);
                    
                  
            })
            //end Navigation Group

            // ->plugin(\TomatoPHP\FilamentPos\FilamentPOSPlugin::make())
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
