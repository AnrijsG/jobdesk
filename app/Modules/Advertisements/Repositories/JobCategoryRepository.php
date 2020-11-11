<?php

namespace App\Modules\Advertisements\Repositories;

class JobCategoryRepository
{
    public const CATEGORY_AGRICULTURE = 'Agriculture, Food and Natural Resources';
    public const CATEGORY_ARTS = 'Arts, Audio/Video Technology and Communications';
    public const CATEGORY_EDUCATION = 'Education and Training';
    public const CATEGORY_PUBLIC_ADMINISTRATION = 'Government and Public Administration';
    public const CATEGORY_TOURISM = 'Hospitality and Tourism';
    public const CATEGORY_IT = 'Information Technology';
    public const CATEGORY_MANUFACTURING = 'Manufacturing';
    public const CATEGORY_SCIENCE = 'Science, Technology, Engineering and Mathematics';
    public const CATEGORY_CONSTRUCTION = 'Architecture and Construction';
    public const CATEGORY_BUSINESS_MANAGEMENT = 'Business Management and Administration';
    public const CATEGORY_FINANCE = 'Finance';
    public const CATEGORY_HEALTH_SCIENCE = 'Health Science';
    public const CATEGORY_HUMAN_SERVICES = 'Human Services';
    public const CATEGORY_LAW = 'Law, Public Safety, Corrections and Security';
    public const CATEGORY_MARKETING = 'Marketing, Sales and Service';
    public const CATEGORY_LOGISTICS = 'Transportation, Distribution and Logistics';

    public const ALL_CATEGORIES = [
        self::CATEGORY_AGRICULTURE,
        self::CATEGORY_ARTS,
        self::CATEGORY_EDUCATION,
        self::CATEGORY_PUBLIC_ADMINISTRATION,
        self::CATEGORY_TOURISM,
        self::CATEGORY_IT,
        self::CATEGORY_MANUFACTURING,
        self::CATEGORY_SCIENCE,
        self::CATEGORY_CONSTRUCTION,
        self::CATEGORY_BUSINESS_MANAGEMENT,
        self::CATEGORY_FINANCE,
        self::CATEGORY_HEALTH_SCIENCE,
        self::CATEGORY_HUMAN_SERVICES,
        self::CATEGORY_LAW,
        self::CATEGORY_MARKETING,
        self::CATEGORY_LOGISTICS,
    ];
}
