<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businesses = Business::latest()->paginate(12);

        return view('business.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $industries = Industry::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $categoriesByIndustryId = Category::query()
            ->orderBy('industry_id')
            ->orderBy('name')
            ->get(['id', 'name', 'industry_id'])
            ->groupBy('industry_id')
            ->map(fn ($group) => $group->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values()->all())
            ->toArray();

        $servicesByCategoryId = Service::query()
            ->orderBy('category_id')
            ->orderBy('name')
            ->get(['id', 'name', 'category_id'])
            ->groupBy('category_id')
            ->map(fn ($group) => $group->map(fn ($s) => ['id' => $s->id, 'name' => $s->name])->values()->all())
            ->toArray();

        return view('business.list', [
            'industries' => $industries,
            'categoriesByIndustryId' => $categoriesByIndustryId,
            'servicesByCategoryId' => $servicesByCategoryId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateBusiness($request);
        $data = $this->mapBusinessData($request, $validated);

        $business = Business::create($data);

        return redirect()
            ->route('business.detail', $business)
            ->with('status', 'Business submitted successfully.');
    }

    /**
     * Show the form for suggesting a business (Someone else's business flow).
     */
    public function createSuggest()
    {
        $industries = Industry::query()->orderBy('name')->pluck('name')->values()->all();

        return view('business.suggest', [
            'industries' => $industries,
        ]);
    }

    /**
     * Store a newly created suggested business.
     */
    public function storeSuggest(Request $request)
    {
        $validated = $this->validateSuggestBusiness($request);
        $data = $this->mapSuggestBusinessData($request, $validated);

        $business = Business::create($data);

        return redirect()
            ->route('businesses.show', $business)
            ->with('status', 'Business suggestion submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        $business->loadMissing(['category', 'service']);

        $reviews = $business->reviews()
            ->with('user')
            ->withCount([
                'votes as like_count' => fn ($query) => $query->where('vote', 1),
                'votes as dislike_count' => fn ($query) => $query->where('vote', -1),
            ])
            ->latest()
            ->get();

        $reviewCount = $reviews->count();
        $avgRating = $reviewCount > 0 ? round((float) $reviews->avg('rating'), 1) : 0;

        $ratingBreakdown = collect([5, 4, 3, 2, 1])
            ->map(fn (int $stars) => $reviews->where('rating', $stars)->count())
            ->values()
            ->all();

        return view('business.show', compact('business', 'reviews', 'avgRating', 'reviewCount', 'ratingBreakdown'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        // Edit flow isn't part of the new React prototype yet.
        return view('business.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business)
    {
        $validated = $this->validateBusiness($request, $business);
        $data = $this->mapBusinessData($request, $validated, $business);

        $business->update($data);

        return redirect()
            ->route('business.detail', $business)
            ->with('status', 'Business updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        $business->delete();

        return redirect()
            ->route('businesses.index')
            ->with('status', 'Business deleted successfully.');
    }

    private function validateBusiness(Request $request, ?Business $business = null): array
    {
        $validated = $request->validate([
            // List flow (Your business?)
            'business_name' => ['required', 'string', 'max:255'],
            'industry_id' => ['required', 'integer', 'exists:industries,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'services' => ['sometimes', 'array'],
            'services.*' => ['integer', 'exists:services,id'],

            'address' => ['required', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'hide_address' => ['sometimes', 'boolean'],

            'business_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('business', 'business_email')->ignore($business?->id),
            ],
            'business_contact_number' => ['required', 'string', 'max:30'],
            'contact_person' => ['nullable', 'string', 'max:255'],

            'website' => ['nullable', 'url', 'max:255'],
            'business_description' => ['nullable', 'string'],
            'business_logo' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:5120'],

            // Working hours inputs. We store them into the existing *_timing columns.
            'sunday_closed' => ['nullable', 'boolean'],
            'monday_closed' => ['nullable', 'boolean'],
            'tuesday_closed' => ['nullable', 'boolean'],
            'wednesday_closed' => ['nullable', 'boolean'],
            'thursday_closed' => ['nullable', 'boolean'],
            'friday_closed' => ['nullable', 'boolean'],
            'saturday_closed' => ['nullable', 'boolean'],

            'sunday_open' => ['nullable', 'date_format:H:i'],
            'sunday_close' => ['nullable', 'date_format:H:i'],
            'monday_open' => ['nullable', 'date_format:H:i'],
            'monday_close' => ['nullable', 'date_format:H:i'],
            'tuesday_open' => ['nullable', 'date_format:H:i'],
            'tuesday_close' => ['nullable', 'date_format:H:i'],
            'wednesday_open' => ['nullable', 'date_format:H:i'],
            'wednesday_close' => ['nullable', 'date_format:H:i'],
            'thursday_open' => ['nullable', 'date_format:H:i'],
            'thursday_close' => ['nullable', 'date_format:H:i'],
            'friday_open' => ['nullable', 'date_format:H:i'],
            'friday_close' => ['nullable', 'date_format:H:i'],
            'saturday_open' => ['nullable', 'date_format:H:i'],
            'saturday_close' => ['nullable', 'date_format:H:i'],
        ]);

        // For every day (Mon-Sun): either mark as closed, or provide opening+closing.
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $messages = [];

        foreach ($days as $day) {
            $isClosed = $request->boolean($day . '_closed');
            if ($isClosed) {
                continue;
            }

            $open = $request->input($day . '_open');
            $close = $request->input($day . '_close');

            if (! $open) {
                $messages[$day . '_open'][] = 'Provide opening time, or mark this day as closed.';
            }

            if (! $close) {
                $messages[$day . '_close'][] = 'Provide closing time, or mark this day as closed.';
            }
        }

        if (! empty($messages)) {
            throw ValidationException::withMessages($messages);
        }

        return $validated;
    }

    private function validateSuggestBusiness(Request $request): array
    {
        // React suggest flow treats email/phone as optional; normalize empty strings to NULL.
        $request->merge([
            'business_email' => $request->filled('business_email') ? $request->input('business_email') : null,
            'business_contact_number' => $request->filled('business_contact_number') ? $request->input('business_contact_number') : null,
        ]);

        return $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],

            'address' => ['required', 'string', 'max:500'],

            'website' => ['nullable', 'url', 'max:255'],
            'business_contact_number' => ['nullable', 'string', 'max:30'],
            'business_email' => ['nullable', 'email', 'max:255', Rule::unique('business', 'business_email')->where('business_email', '!=', '')],

            // These are not collected in the suggest UI, but we accept them as optional for forward-compat.
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
        ]);
    }

    private function mapBusinessData(Request $request, array $validated, ?Business $business = null): array
    {
        $industryId = (int) $validated['industry_id'];

        $categoryId = $validated['category_id'] ?? null;
        if (! $categoryId) {
            $categoryId = Category::query()
                ->where('industry_id', $industryId)
                ->orderBy('id')
                ->value('id');

            if (! $categoryId) {
                $categoryId = Category::create([
                    'industry_id' => $industryId,
                    'name' => 'General',
                    'description' => null,
                ])->id;
            }
        }

        $requestedServiceIds = collect($validated['services'] ?? [])
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values()
            ->all();

        // Filter selected services to the chosen category, then pick the first as the required FK.
        if (! empty($requestedServiceIds)) {
            $allowedServiceIds = Service::query()
                ->whereIn('id', $requestedServiceIds)
                ->where('category_id', (int) $categoryId)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();

            $serviceIdsSelected = array_values(array_filter($requestedServiceIds, static fn ($id) => in_array($id, $allowedServiceIds, true)));
        }

        $serviceId = null;
        $tags = null;

        if (isset($serviceIdsSelected) && ! empty($serviceIdsSelected)) {
            $serviceId = (int) $serviceIdsSelected[0];

            $serviceNamesById = Service::query()
                ->whereIn('id', $serviceIdsSelected)
                ->pluck('name', 'id');

            $tags = collect($serviceIdsSelected)
                ->map(fn ($id) => $serviceNamesById[$id] ?? null)
                ->filter()
                ->values()
                ->all();
        } else {
            $serviceId = Service::query()
                ->where('category_id', (int) $categoryId)
                ->orderBy('id')
                ->value('id');

            if (! $serviceId) {
                $serviceId = Service::create([
                    'category_id' => (int) $categoryId,
                    'name' => 'General Service',
                    'description' => null,
                ])->id;
            }
        }

        $data = [
            'owner_id' => null, // claim flow handled later
            'contact_number' => $validated['business_contact_number'],
            'business_name' => $validated['business_name'],
            'business_email' => $validated['business_email'],
            'business_contact_number' => $validated['business_contact_number'],
            'website' => $validated['website'] ?? null,
            'business_description' => $validated['business_description'] ?? null,
            'country' => $validated['country'] ?? '',
            'state' => $validated['state'] ?? '',
            'city' => $validated['city'] ?? '',
            'pincode' => $validated['pincode'] ?? '',
            'address_line_1' => $validated['address'],
            'industry_id' => $industryId,
            'category_id' => (int) $categoryId,
            'service_id' => $serviceId,
            'tags' => $tags,
            'hear_from' => null,
            'contact_person' => $validated['contact_person'] ?? null,
            'hide_address' => $request->boolean('hide_address'),

            'sunday_timing' => $this->composeDayTiming($request, 'sunday'),
            'monday_timing' => $this->composeDayTiming($request, 'monday'),
            'tuesday_timing' => $this->composeDayTiming($request, 'tuesday'),
            'wednesday_timing' => $this->composeDayTiming($request, 'wednesday'),
            'thursday_timing' => $this->composeDayTiming($request, 'thursday'),
            'friday_timing' => $this->composeDayTiming($request, 'friday'),
            'saturday_timing' => $this->composeDayTiming($request, 'saturday'),
        ];

        if ($request->hasFile('business_logo')) {
            $data['business_logo'] = $request->file('business_logo')->store('business/logos', 'public');
        } elseif ($business) {
            $data['business_logo'] = $business->business_logo;
        }

        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $request->file('cover_photo')->store('business/covers', 'public');
        } elseif ($business) {
            $data['cover_photo'] = $business->cover_photo;
        }

        return $data;
    }

    private function composeDayTiming(Request $request, string $day): ?string
    {
        if ($request->boolean($day . '_closed')) {
            return 'closed';
        }

        $open = $request->input($day . '_open');
        $close = $request->input($day . '_close');

        if ($open && $close) {
            return $open . ' - ' . $close;
        }

        return null;
    }

    private function mapSuggestBusinessData(Request $request, array $validated): array
    {
        $industryId = $this->resolveIndustryId($validated['industry']);
        $categoryId = $this->resolveCategoryId($industryId, null);

        $serviceId = $this->resolveServiceId($categoryId, []);

        $businessEmail = $validated['business_email'] ?? null;
        $businessPhone = $validated['business_contact_number'] ?? null;

        return [
            'owner_id' => null, // claim flow handled later
            'contact_number' => $businessPhone ?: '',
            'business_name' => $validated['business_name'],
            'business_email' => $businessEmail ?: '',
            'business_contact_number' => $businessPhone ?: '',
            'website' => $validated['website'] ?? null,
            'country' => $validated['country'] ?? '',
            'state' => $validated['state'] ?? '',
            'city' => $validated['city'] ?? '',
            'pincode' => $validated['pincode'] ?? '',
            'address_line_1' => $validated['address'],
            'industry_id' => $industryId,
            'category_id' => $categoryId,
            'service_id' => $serviceId,
            'tags' => null,
            'hear_from' => null,
            'contact_person' => null,
            'hide_address' => false,
        ];
    }

    private function resolveIndustryId(string $industryName): int
    {
        $industryName = trim($industryName);

        return Industry::firstOrCreate(
            ['name' => $industryName],
            ['description' => null]
        )->id;
    }

    private function resolveCategoryId(int $industryId, ?string $categoryName): int
    {
        $categoryName = $categoryName !== null ? trim($categoryName) : null;

        if ($categoryName !== null && $categoryName !== '') {
            return Category::firstOrCreate(
                ['industry_id' => $industryId, 'name' => $categoryName],
                ['description' => null]
            )->id;
        }

        $existing = Category::query()
            ->where('industry_id', $industryId)
            ->orderBy('id')
            ->first();

        if ($existing) {
            return $existing->id;
        }

        return Category::create([
            'industry_id' => $industryId,
            'name' => 'General',
            'description' => null,
        ])->id;
    }

    /**
     * @param array<int, string> $serviceNames
     */
    private function resolveServiceId(int $categoryId, array $serviceNames): int
    {
        $serviceNames = array_values(array_filter(array_map(static fn ($s) => is_string($s) ? trim($s) : '', $serviceNames), static fn ($s) => $s !== ''));

        if (count($serviceNames) > 0) {
            $first = $serviceNames[0];

            // Ensure the first selected service exists so we can populate required `service_id`.
            $service = Service::firstOrCreate(
                ['category_id' => $categoryId, 'name' => $first],
                ['description' => null]
            );

            // Also ensure remaining selected services exist (for later use; stored as tags now).
            foreach (array_slice($serviceNames, 1) as $name) {
                Service::firstOrCreate(
                    ['category_id' => $categoryId, 'name' => $name],
                    ['description' => null]
                );
            }

            return $service->id;
        }

        // No selected services in UI: pick/create a safe default.
        $existing = Service::query()
            ->where('category_id', $categoryId)
            ->orderBy('id')
            ->first();

        if ($existing) {
            return $existing->id;
        }

        return Service::create([
            'category_id' => $categoryId,
            'name' => 'General Service',
            'description' => null,
        ])->id;
    }
}
