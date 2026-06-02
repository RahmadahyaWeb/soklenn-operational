<?php

use App\Models\Category;
use App\Traits\AuthorizesCrud;
use Flux\Flux;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

new class extends Component
{
    use AuthorizesCrud, AuthorizesRequests;

    public ?Category $category = null;

    public $name;

    public $type = 'operational';

    public function mount(?Category $category = null)
    {
        if ($category && $category->exists) {

            $this->authorizeUpdate($category);

            $this->category = $category;

            $this->name = $category->name;
            $this->type = $category->type;

        } else {

            $this->authorizeStore(Category::class);

        }
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $this->category
                    ? 'unique:categories,name,'.$this->category->id
                    : 'unique:categories,name',
            ],

            'type' => [
                'required',
                'in:operational,non_operational',
            ],
        ]);

        return $this->transaction(function () {

            if ($this->category) {

                $this->authorizeUpdate($this->category);

                $this->category->update([
                    'name' => $this->name,
                    'type' => $this->type,
                ]);

                Flux::toast(
                    heading: 'Success',
                    text: 'Category updated successfully',
                    variant: 'success'
                );

            } else {

                $this->authorizeStore(Category::class);

                Category::create([
                    'name' => $this->name,
                    'type' => $this->type,
                ]);

                Flux::toast(
                    heading: 'Success',
                    text: 'Category created successfully',
                    variant: 'success'
                );

            }

            $this->redirect(route('categories.index'), navigate: true);

        });
    }
};
