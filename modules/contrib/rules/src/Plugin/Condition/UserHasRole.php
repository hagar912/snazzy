<?php

namespace Drupal\rules\Plugin\Condition;

use Drupal\rules\Core\RulesConditionBase;
use Drupal\rules\Exception\InvalidArgumentException;
use Drupal\user\UserInterface;

/**
 * Provides a 'User has roles(s)' condition.
 *
 * @Condition(
 *   id = "rules_user_has_role",
 *   label = @Translation("User has role(s)"),
 *   category = @Translation("User"),
 *   context_definitions = {
 *     "user" = @ContextDefinition("entity:user",
 *       label = @Translation("User"),
 *       description = @Translation("Specifies the user account to check."),
 *     ),
 *     "roles" = @ContextDefinition("entity:user_role",
 *       label = @Translation("Roles"),
 *       description = @Translation("Specifies the roles to check for."),
 *       multiple = TRUE
 *     ),
 *     "operation" = @ContextDefinition("string",
 *       label = @Translation("Match roles"),
 *       description = @Translation("If matching against all selected roles, the user must have <em>all</em> the roles selected."),
 *       assignment_restriction = "input",
 *       default_value = "AND",
 *       required = FALSE
 *     ),
 *   }
 * )
 *
 * @todo Add access callback information from Drupal 7.
 */
class UserHasRole extends RulesConditionBase {

  /**
   * Evaluate if user has role(s).
   *
   * @param \Drupal\user\UserInterface $user
   *   The account to check.
   * @param \Drupal\user\RoleInterface[] $roles
   *   Array of user roles.
   * @param string $operation
   *   Either "AND": user has all of roles.
   *   Or "OR": user has at least one of all roles.
   *   Defaults to "AND".
   *
   * @return bool
   *   TRUE if the user has the role(s).
   */
 /* protected function doEvaluate(UserInterface $user, array $roles, $operation = 'AND') {

    $rids = array_map(function ($role) {
      return $role->id();
    }, $roles);

    switch ($operation) {
      case 'OR':
        return (bool) array_intersect($rids, $user->getRoles());

      case 'AND':
        return (bool) !array_diff($rids, $user->getRoles());

      default:
        throw new InvalidArgumentException('Either use "AND" or "OR". Leave empty for default "AND" behavior.');
    }
  }*/

  protected function doEvaluate(UserInterface $account, array $roles, $operation = 'AND') {

     $rids = array_map(function ($role) {
       static $drupal_roles;
       if (!$drupal_roles) {
         $drupal_roles = Role::loadMultiple();
       }
       // If the role is not a role entity yet, load it if we can.
       if (!$role instanceof Role) {
         // Try to load the role by role id.
         if ($r = Role::load(strtolower($role))) {
           $role = $r;
         }
         else {
           // Try to load the role by role label.
           foreach ($drupal_roles as $drupal_role) {
             if ($drupal_role->get('label') == trim($role)) {
               $role = $drupal_role;
               break;
             }
           }
         }
       }
       return $role->id();
     }, $roles);
  }
}
