<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $type
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $children
 * @property-read int|null $children_count
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Template[] $templates
 * @property-read int|null $templates_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Query\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FAQ
 *
 * @property int $id
 * @property string $question
 * @property string|null $answer
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ newQuery()
 * @method static \Illuminate\Database\Query\Builder|FAQ onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ query()
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|FAQ withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FAQ withoutTrashed()
 */
	class FAQ extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GeoSegment
 *
 * @property int $id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Link[] $links
 * @property-read int|null $links_count
 * @method static \Illuminate\Database\Eloquent\Builder|GeoSegment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GeoSegment newQuery()
 * @method static \Illuminate\Database\Query\Builder|GeoSegment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GeoSegment query()
 * @method static \Illuminate\Database\Eloquent\Builder|GeoSegment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GeoSegment whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GeoSegment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GeoSegment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GeoSegment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|GeoSegment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GeoSegment withoutTrashed()
 */
	class GeoSegment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Instance
 *
 * @property int $id
 * @property string $name
 * @property array $values
 * @property int $template_id
 * @property int|null $location_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Instance[] $grouping
 * @property-read int|null $grouping_count
 * @property-read \App\Models\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @property-read \App\Models\Template $template
 * @method static \Illuminate\Database\Eloquent\Builder|Instance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Instance newQuery()
 * @method static \Illuminate\Database\Query\Builder|Instance onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Instance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Instance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instance whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instance whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instance whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instance whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instance whereValues($value)
 * @method static \Illuminate\Database\Query\Builder|Instance withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Instance withoutTrashed()
 */
	class Instance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Link
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GeoSegment[] $geoSegments
 * @property-read int|null $geo_segments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|Link newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Link newQuery()
 * @method static \Illuminate\Database\Query\Builder|Link onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Link query()
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Link withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Link withoutTrashed()
 */
	class Link extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property array $data
 * @property int|null $project_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Instance[] $instances
 * @property-read int|null $instances_count
 * @property-read \App\Models\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $team
 * @property-read int|null $team_count
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Query\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Location withoutTrashed()
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Membership
 *
 * @property int $id
 * @property int $team_id
 * @property int $user_id
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUserId($value)
 */
	class Membership extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\News
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property int $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News query()
 * @method static \Illuminate\Database\Eloquent\Builder|News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedAt($value)
 */
	class News extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property int $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Query\Builder|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Permission withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Permission withoutTrashed()
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $location_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Simulation[] $simulations
 * @property-read int|null $simulations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Query\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Project withoutTrashed()
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Property
 *
 * @property int $id
 * @property string $name
 * @property string $dataType
 * @property string $inputType
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $symbolic_name
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TemplateProperty[] $templateProperties
 * @property-read int|null $template_properties_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $units
 * @property-read int|null $units_count
 * @method static \Illuminate\Database\Eloquent\Builder|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property newQuery()
 * @method static \Illuminate\Database\Query\Builder|Property onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereDataType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereInputType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereSymbolicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Property withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Property withoutTrashed()
 */
	class Property extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PropertyGroup
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyGroup newQuery()
 * @method static \Illuminate\Database\Query\Builder|PropertyGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|PropertyGroup withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PropertyGroup withoutTrashed()
 */
	class PropertyGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Simulation
 *
 * @property int $id
 * @property string $status
 * @property array $targetData
 * @property int $project_id
 * @property int $target_id
 * @property int $simulation_type_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\SimulationType $simulationType
 * @property-read \App\Models\Target $target
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Simulation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereSimulationTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereTargetData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Simulation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Simulation withoutTrashed()
 */
	class Simulation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SimulationType
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $value
 * @property int $default_unit_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Simulation[] $simulations
 * @property-read int|null $simulations_count
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType newQuery()
 * @method static \Illuminate\Database\Query\Builder|SimulationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType query()
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType whereDefaultUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimulationType whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|SimulationType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SimulationType withoutTrashed()
 */
	class SimulationType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Target
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $value
 * @property int $default_unit_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Simulation[] $simulations
 * @property-read int|null $simulations_count
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|Target newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Target newQuery()
 * @method static \Illuminate\Database\Query\Builder|Target onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Target query()
 * @method static \Illuminate\Database\Eloquent\Builder|Target whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Target whereDefaultUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Target whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Target whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Target whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Target whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Target whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Target whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|Target withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Target withoutTrashed()
 */
	class Target extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Team
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property bool $personal_team
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Instance[] $instances
 * @property-read int|null $instances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Link[] $links
 * @property-read int|null $links_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeamInvitation[] $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeamRole[] $teamRoles
 * @property-read int|null $team_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\TeamFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TeamInvitation
 *
 * @property int $id
 * @property int $team_id
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamInvitation whereUpdatedAt($value)
 */
	class TeamInvitation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TeamRole
 *
 * @property int $id
 * @property int $team_id
 * @property string $role
 * @property array|null $permissions
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole newQuery()
 * @method static \Illuminate\Database\Query\Builder|TeamRole onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|TeamRole withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TeamRole withoutTrashed()
 */
	class TeamRole extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Template
 *
 * @property int $id
 * @property string $name
 * @property array $values
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Instance[] $instances
 * @property-read int|null $instances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TemplateGrouping[] $templateGrouping
 * @property-read int|null $template_grouping_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TemplateProperty[] $templateProperties
 * @property-read int|null $template_properties_count
 * @method static \Illuminate\Database\Eloquent\Builder|Template newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Template newQuery()
 * @method static \Illuminate\Database\Query\Builder|Template onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Template query()
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereValues($value)
 * @method static \Illuminate\Database\Query\Builder|Template withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Template withoutTrashed()
 */
	class Template extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TemplateGrouping
 *
 * @property int $id
 * @property int $order
 * @property bool $is_summary
 * @property int $template_id
 * @property int $property_group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PropertyGroup $propertyGroup
 * @property-read \App\Models\Template $template
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TemplateProperty[] $templateProperties
 * @property-read int|null $template_properties_count
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping query()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping whereIsSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping wherePropertyGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateGrouping whereUpdatedAt($value)
 */
	class TemplateGrouping extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TemplateProperty
 *
 * @property int $id
 * @property bool $required
 * @property int $template_id
 * @property int $property_id
 * @property int $default_unit_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $default_value
 * @property int $order
 * @property bool|null $is_summary
 * @property int|null $grouping_id
 * @property-read \App\Models\Property $property
 * @property-read \App\Models\Template $template
 * @property-read \App\Models\TemplateGrouping|null $templateGroup
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty newQuery()
 * @method static \Illuminate\Database\Query\Builder|TemplateProperty onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty query()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereDefaultUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereGroupingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereIsSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|TemplateProperty withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TemplateProperty withoutTrashed()
 */
	class TemplateProperty extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Unit
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $symbol
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Property[] $properties
 * @property-read int|null $properties_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SimulationType[] $simulationTypes
 * @property-read int|null $simulation_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Target[] $targets
 * @property-read int|null $targets_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TemplateProperty[] $templateProperties
 * @property-read int|null $template_properties_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UnitConversion[] $unitCoversionsFrom
 * @property-read int|null $unit_coversions_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UnitConversion[] $unitCoversionsTo
 * @property-read int|null $unit_coversions_to_count
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Query\Builder|Unit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Unit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Unit withoutTrashed()
 */
	class Unit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UnitConversion
 *
 * @property int $id
 * @property string $expression
 * @property int $from_id
 * @property int $to_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Unit $from
 * @property-read \App\Models\Unit $to
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion newQuery()
 * @method static \Illuminate\Database\Query\Builder|UnitConversion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion whereExpression($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion whereFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitConversion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|UnitConversion withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UnitConversion withoutTrashed()
 */
	class UnitConversion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

