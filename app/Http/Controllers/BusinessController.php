<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $industries = Industry::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $business = null;

        return view('business.create', compact('industries', 'categories', 'services', 'business'));
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
            ->route('businesses.show', $business)
            ->with('status', 'Business submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        return view('business.show', compact('business'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        $industries = Industry::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $services = Service::orderBy('name')->get();

        return view('business.create', compact('business', 'industries', 'categories', 'services'));
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
            ->route('businesses.show', $business)
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
        return $request->validate([
            'owner_first_name' => ['required', 'string', 'max:255'],
            'owner_last_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:30'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('business', 'business_email')->ignore($business?->id),
            ],
            'business_contact_number' => ['required', 'string', 'max:30'],
            'website' => ['nullable', 'url', 'max:255'],
            'business_description' => ['nullable', 'string'],
            'business_logo' => ['nullable', 'image', 'max:3072'],
            'cover_photo' => ['nullable', 'image', 'max:5120'],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'pincode' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string', 'max:500'],
            'industry_id' => ['required', 'exists:industries,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'service_id' => ['required', 'exists:services,id'],
            'tags_input' => ['nullable', 'string'],
            'hear_from' => ['nullable', 'string', 'max:50'],

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
    }

    private function mapBusinessData(Request $request, array $validated, ?Business $business = null): array
    {
        $tags = collect(explode(',', $validated['tags_input'] ?? ''))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $data = [
            'owner_first_name' => $validated['owner_first_name'],
            'owner_last_name' => $validated['owner_last_name'],
            'contact_number' => $validated['contact_number'],
            'business_name' => $validated['business_name'],
            'business_email' => $validated['business_email'],
            'business_contact_number' => $validated['business_contact_number'],
            'website' => $validated['website'] ?? null,
            'business_description' => $validated['business_description'] ?? null,
            'country' => $validated['country'],
            'state' => $validated['state'],
            'city' => $validated['city'],
            'pincode' => $validated['pincode'],
            'address_line_1' => $validated['address_line_1'],
            'industry_id' => $validated['industry_id'],
            'category_id' => $validated['category_id'],
            'service_id' => $validated['service_id'],
            'tags' => $tags,
            'hear_from' => $validated['hear_from'] ?? null,
            'sunday_timing' => $this->composeTiming($request, 'sunday'),
            'monday_timing' => $this->composeTiming($request, 'monday'),
            'tuesday_timing' => $this->composeTiming($request, 'tuesday'),
            'wednesday_timing' => $this->composeTiming($request, 'wednesday'),
            'thursday_timing' => $this->composeTiming($request, 'thursday'),
            'friday_timing' => $this->composeTiming($request, 'friday'),
            'saturday_timing' => $this->composeTiming($request, 'saturday'),
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

    private function composeTiming(Request $request, string $day): ?string
    {
        if ($request->boolean($day.'_closed')) {
            return 'Closed';
        }

        $open = $request->input($day.'_open');
        $close = $request->input($day.'_close');

        if ($open && $close) {
            return $open.' - '.$close;
        }

        return null;
    }
}
