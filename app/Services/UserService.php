<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * UserService - Sử dụng Try-Catch thay vì Transaction
 * Đơn giản và dễ hiểu cho người mới học
 */
class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * LẤY DANH SÁCH USER VỚI BỘ LỌC
     */
    public function getUsersWithFilters(array $filters, int $perPage = 4)
    {
        return $this->userRepository->getAllWithFilters($filters, $perPage);
    }

    /**
     * LẤY TẤT CẢ ROLE
     */
    public function getAllRoles()
    {
        return $this->userRepository->getAllRoles();
    }

    /**
     * TÌM USER THEO ID
     */
    public function findUser(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * TẠO USER MỚI
     * Sử dụng try-catch để xử lý lỗi và cleanup
     */
    public function createUser(array $data): User
    {
        // Biến lưu tên file avatar đã upload (để xóa nếu có lỗi)
        $uploadedAvatarFileName = null;

        try {
            $userData = [
                'name' => $data['full_name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ];

            if (isset($data['status'])) {
                $isActive = ($data['status'] == 1);
                $userData['is_active'] = $isActive ? 1 : 0;
                $userData['is_verified'] = $isActive ? 1 : 0;
            }

            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                $uploadedAvatarFileName = $this->uploadAvatar($data['avatar']);
                $userData['avatar_url'] = $uploadedAvatarFileName;
            }

            $user = $this->userRepository->create($userData);

            if (isset($data['role']) && !empty($data['role'])) {
                $this->userRepository->createUserRole($user->id, $data['role']);
            }

            return $user;

        } catch (\Exception $e) {
            // NẾU CÓ BẤT KỲ LỖI NÀO XẢY RA:
            
            // Xóa file avatar đã upload (nếu có) để tránh rác
            if ($uploadedAvatarFileName) {
                $this->deleteAvatar($uploadedAvatarFileName);
            }
            
            // Ném lỗi lên Controller để xử lý
            throw new \Exception('Không thể tạo user: ' . $e->getMessage());
        }
    }

    public function updateUser(User $user, array $data): User
    {
        $oldAvatarFileName = $user->avatar_url;
        $newAvatarFileName = null;

        try {
            $updateData = [
                'name' => $data['full_name'],
                'email' => $data['email'],
            ];

            if (isset($data['phone'])) {
                $updateData['phone'] = $data['phone'];
            }

            if (isset($data['status'])) {
                $updateData['is_active'] = ($data['status'] == 1) ? 1 : 0;
            }

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                $newAvatarFileName = $this->uploadAvatar($data['avatar']);
                $updateData['avatar_url'] = $newAvatarFileName;
            }

            $updatedUser = $this->userRepository->update($user, $updateData);

            if ($newAvatarFileName && $oldAvatarFileName) {
                $this->deleteAvatar($oldAvatarFileName);
            }

            if (isset($data['role']) && !empty($data['role'])) {
                $this->userRepository->updateOrCreateUserRole($updatedUser->id, $data['role']);
            }

            return $updatedUser;

        } catch (\Exception $e) {
            if ($newAvatarFileName) {
                $this->deleteAvatar($newAvatarFileName);
            }
            throw new \Exception('Không thể cập nhật user: ' . $e->getMessage());
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
            // BƯỚC 1: Tìm user
            $user = $this->userRepository->findById($id);
            if (!$user) {
                return false; // Không tìm thấy user
            }

            // BƯỚC 2: Xóa role của user trước
            $this->userRepository->deleteUserRole($user->id);

            // BƯỚC 3: Xóa user khỏi database
            $deleted = $this->userRepository->delete($user);

            // BƯỚC 4: Nếu xóa thành công, xóa avatar file
            if ($deleted && $user->avatar_url) {
                $this->deleteAvatar($user->avatar_url);
            }

            return $deleted;

        } catch (\Exception $e) {
            // Log lỗi hoặc xử lý theo cách khác
            throw new \Exception('Không thể xóa user: ' . $e->getMessage());
        }
    }

    /**
     * UPLOAD AVATAR
     * Trả về tên file đã upload
     */
    private function uploadAvatar(UploadedFile $avatarFile): string
    {
        try {
            $fileName = time() . '_' . uniqid() . '.' . $avatarFile->getClientOriginalExtension();
            $avatarFile->storeAs('avatars', $fileName, 'public');
            return $fileName;

        } catch (\Exception $e) {
            throw new \Exception('Không thể upload avatar: ' . $e->getMessage());
        }
    }

    /**
     * XÓA AVATAR FILE
     */
    private function deleteAvatar(?string $avatarFileName): void
    {
        if ($avatarFileName && Storage::disk('public')->exists('avatars/' . $avatarFileName)) {
            Storage::disk('public')->delete('avatars/' . $avatarFileName);
        }
    }

    /**
     * KIỂM TRA USER CÓ TỒN TẠI KHÔNG
     */
    public function userExists(int $id): bool
    {
        try {
            return $this->userRepository->findById($id) !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * ĐẾM TỔNG SỐ USER
     */
    public function getUserCount(array $filters = []): int
    {
        try {
            $result = $this->getUsersWithFilters($filters, 1);
            return $result->total();
        } catch (\Exception $e) {
            return 0;
        }
    }
}