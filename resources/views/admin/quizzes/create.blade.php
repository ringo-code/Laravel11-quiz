<x-admin-layout>
    <section class="text-gray-600 body-font relative">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">クイズ新規登録</h1>
            </div>
            <div class="lg:w-1/2 md:w-2/3 mx-auto">
                {{-- フォーム --}}
                <form action="{{ route('admin.categories.quizzes.store', ['categoryId' => $categoryId]) }}" method="POST">
                    @csrf
                    <div class="p-2 w-full">
                        <div class="relative">
                            <label for="question" class="leading-7 text-sm text-gray-600">問題文</label>
                            {{-- textareaではタグの前後に空白を入れない --}}
                            <textarea id="question" name="question" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"
                            >{{ old('question') }}</textarea>
                        </div>

                        {{-- クイズ説明文のバリデーションを表示 --}}
                        @error('question')
                            <div class="alert alert-danger text-red-700">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="p-2 w-full">
                        <div class="relative">
                            <label for="explanation" class="leading-7 text-sm text-gray-600">解説</label>
                            {{-- textareaではタグの前後に空白を入れない --}}
                            <textarea id="explanation" name="explanation" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"
                            >{{ old('explanation') }}</textarea>
                        </div>

                        {{-- クイズ説明文のバリデーションを表示 --}}
                        @error('explanation')
                            <div class="alert alert-danger text-red-700">{{ $message }}</div>
                        @enderror
                    </div>

                    @for ($i = 1; $i <= 4; $i++)
                        <div class="p-2 w-full">
                            <div class="relative">
                                <label for="content{{ $i }}" class="leading-7 text-sm text-gray-600">選択肢{{ $i }}</label>
                                <input type="text" id="content{{ $i }}" name="content{{ $i }}" value="{{ old('content' . $i) }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>

                            {{-- 選択肢のバリデーションを表示 --}}
                            @error('content' . $i)
                                <div class="alert alert-danger text-red-700">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="p-2 w-full">
                            <div class="relative">
                                <label for="is_correct{{ $i }}" class="leading-7 text-sm text-gray-600">選択肢{{ $i }}の正解・不正解</label>
                                <select
                                    name="isCorrect{{ $i }}"
                                    id="isCorrect{{ $i }}"
                                    value="{{ old('is_correct' . $i) }}"
                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                    >
                                    <option value="1">正解</option>
                                    <option value="0">不正解</option>
                                </select>
                            </div>

                            {{-- 正解・不正解のバリデーションを表示 --}}
                            @error('is_correct' . $i)
                                <div class="alert alert-danger text-red-700">{{ $message }}</div>
                            @enderror
                        </div>
                    @endfor

                    <div class="p-2 w-full">
                        <button type="submit" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                            登録
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-admin-layout>
