<?php

namespace App\Filament\Resources\KategoriResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MenuRelationManagers extends RelationManager
{
  protected static string $relationship = 'menus';
  protected static ?string $title = 'Menu';

  // public function form(Form $form): Form
  // {
  //   return $form
  //     ->schema([
  //       Forms\Components\TextInput::make('name')
  //         ->required()
  //         ->maxLength(255),

  //       Forms\Components\TextInput::make('rating')
  //         ->required()
  //         ->maxLength(255),

  //       Forms\Components\FileUpload::make('photo')
  //         ->required()
  //         ->image(),

  //       Forms\Components\Textarea::make('message')
  //         ->required()
  //     ]);
  // }

  public function table(Table $table): Table
  {
    return $table
      ->recordTitleAttribute('nama')
      ->columns([
        Tables\Columns\ImageColumn::make('gambar')
          ->circular(),


        Tables\Columns\TextColumn::make('nama')
          ->searchable(),

        Tables\Columns\TextColumn::make('kategoris.nama'),


        Tables\Columns\TextColumn::make('is_tersedia')
          ->label('Status')
          ->badge()
          ->getStateUsing(fn($record) => $record->is_tersedia ? 'Tersedia' : 'Tidak Tersedia')
          ->colors([
            'success' => 'Tersedia',
            'danger' => 'Tidak Tersedia',
          ]),
      ])
      ->filters([
        //
      ])
      ->actions([])
      ->bulkActions([]);
  }
}
